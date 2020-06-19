<?php

namespace App\Http\Controllers;

use App\App;
use App\Country;
use App\Http\Requests\CreateAppRequest;
use App\Http\Requests\DeleteAppRequest;
use App\Services\ApigeeService;
use App\Services\ProductLocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AppController extends Controller {
	public function index() {
		$apps = App::with(['products', 'country'])
			->byUserEmail(\Auth::user()->email)
			->orderBy('updated_at', 'desc')
			->get()
			->groupBy('status');

		return view('templates.apps.index', [
			'approvedApps' => $apps['approved'] ?? [],
			'revokedApps' => $apps['revoked'] ?? [],
		]);
	}

	public function create(ProductLocationService $productLocationService) {
		[$products, $countries] = $productLocationService->fetch();

		return view('templates.apps.create', [
			'products' => $products,
			'productCategories' => array_keys($products->toArray()),
			'countries' => $countries ?? '',
		]
		);
	}

	public function store(CreateAppRequest $request) {
		$validated = $request->validated();
		$countries = Country::pluck('id', 'code');

		$data = [
			'name' => Str::slug($validated['name']),
			'apiProducts' => $request->products,
			'keyExpiresIn' => -1,
			'attributes' => [
				[
					'name' => 'DisplayName',
					'value' => $validated['name'],
				],
				[
					'name' => 'Description',
					'value' => preg_replace('/[<>"]*/', '', strip_tags($validated['description'])),
				],
			],
			'callbackUrl' => preg_replace('/[<>"]*/', '', strip_tags($validated['url'])) ?? '',
		];

		$createdResponse = ApigeeService::createApp($data);

		if (strpos($createdResponse, 'duplicate key') !== false) {
			return response()->json(['success' => false, 'message' => 'There is already an app with that name.'], 409);
		}

		$attributes = ApigeeService::getAppAttributes($createdResponse['attributes']);
		$credentials = ApigeeService::getLatestCredentials($createdResponse['credentials']);
		unset($credentials['apiProducts']);

		$app = App::create([
			"aid" => $createdResponse['appId'],
			"name" => $createdResponse['name'],
			"display_name" => $validated['name'],
			"callback_url" => $createdResponse['callbackUrl'],
			"attributes" => $attributes,
			"credentials" => $credentials,
			"developer_id" => $createdResponse['developerId'],
			"status" => $createdResponse['status'],
			"description" => $attributes['Description'] ?? '',
			"country_id" => $countries[$validated['country']],
			"updated_at" => date('Y-m-d H:i:s', $createdResponse['lastModifiedAt'] / 1000),
			"created_at" => date('Y-m-d H:i:s', $createdResponse['createdAt'] / 1000),
		]);

		$app->products()->sync($request->products);

		if ($request->ajax()) {
			return response()->json(['response' => $createdResponse]);
		}

		return redirect(route('app.index'));
	}

	public function edit(ProductLocationService $productLocationService, App $app, Request $request) {
		[$products, $countries] = $productLocationService->fetch();

		$app->load('products');

		return view('templates.apps.edit', [
			'products' => $products,
			'countries' => $countries ?? '',
			'data' => $app,
			'selectedProducts' => array_column($app['products']->toArray(), 'name'),
		]);
	}

	public function update(App $app, CreateAppRequest $request) {
		$validated = $request->validated();
		$countries = Country::pluck('id', 'code');

		$data = [
			'name' => $validated['name'],
			'key' => $validated['key'],
			'apiProducts' => $request->products,
			'originalProducts' => $validated['original_products'],
			'keyExpiresIn' => -1,
			'attributes' => [
				[
					'name' => 'DisplayName',
					'value' => $validated['new_name'] ?? $validated['name'],
				],
				[
					'name' => 'Description',
					'value' => preg_replace('/[<>"]*/', '', strip_tags($validated['description'])),
				],
				[
					'name' => 'Country',
					'value' => $validated['country'],
				],
			],
			'callbackUrl' => preg_replace('/[<>"]*/', '', strip_tags($validated['url'])) ?? '',
		];

		$updatedResponse = ApigeeService::updateApp($data);

		$app->update([
			'display_name' => $data['attributes'][0]['value'],
			'attributes' => $data['attributes'],
			'callback_url' => $data['callbackUrl'],
			'description' => $data['attributes'][1]['value'],
			'country_id' => $countries[$validated['country']],
		]);

		$app->products()->sync($data['apiProducts']);

		if ($request->ajax()) {
			return response()->json(['response' => $updatedResponse]);
		}

		return redirect(route('app.index'));
	}

	public function destroy(App $app, DeleteAppRequest $request) {
		$validated = $request->validated();

		$user = \Auth::user();
		ApigeeService::delete("developers/{$user->email}/apps/{$validated['name']}");

		$app->delete();

		return redirect(route('app.index'));
	}

	public function getCredentials(App $app, $type)
	{
		if(auth()->user()->developer_id !== $app->developer_id) return "You can't access app keys that don't belong to you.";

		$credentials = ApigeeService::get('apps/' . $app->aid)['credentials'];
		$credentials = ApigeeService::getLatestCredentials($credentials);

		if($type === 'all'){
			return $credentials;
		}

		return $credentials[$type];
	}
}
