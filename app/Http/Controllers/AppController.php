<?php

namespace App\Http\Controllers;

use App\App;
use App\Country;
use App\Http\Requests\CreateAppRequest;
use App\Http\Requests\DeleteAppRequest;
use App\Mail\GoLiveMail;
use App\Services\ApigeeService;
use App\Services\KycService;
use App\Services\ProductLocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AppController extends Controller
{
	public function index()
	{
		$apps = App::with(['products.countries', 'country'])
			->byUserEmail(\Auth::user()->email)
			->orderBy('updated_at', 'desc')
			->get()
			->groupBy('status');

		return view('templates.apps.index', [
			'approvedApps' => $apps['approved'] ?? [],
			'revokedApps' => $apps['revoked'] ?? [],
		]);
	}

	public function create(ProductLocationService $productLocationService)
	{
		[$products, $countries] = $productLocationService->fetch();

		return view('templates.apps.create', [
			'products' => $products,
			'productCategories' => array_keys($products->toArray()),
			'countries' => $countries ?? '',
		]);
	}

	public function store(CreateAppRequest $request)
	{
		$validated = $request->validated();
		$countriesByCode = Country::pluck('iso', 'code');

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
				[
					'name' => 'Country',
					'value' => $validated['country'],
				],
				[
					'name' => 'location',
					'value' => $countriesByCode[$validated['country']] ?? "",
				],
			],
			'callbackUrl' => preg_replace('/[<>"]*/', '', strip_tags($validated['url'])) ?? '',
		];

		$createdResponse = ApigeeService::createApp($data);

		if (strpos($createdResponse, 'duplicate key') !== false) {
			return response()->json(['success' => false, 'message' => 'There is already an app with that name.'], 409);
		}

		$attributes = ApigeeService::getAppAttributes($createdResponse['attributes']);

		$app = App::create([
			"aid" => $createdResponse['appId'],
			"name" => $createdResponse['name'],
			"display_name" => $validated['name'],
			"callback_url" => $createdResponse['callbackUrl'],
			"attributes" => $attributes,
			"credentials" => $createdResponse['credentials'],
			"developer_id" => $createdResponse['developerId'],
			"status" => $createdResponse['status'],
			"description" => $attributes['Description'] ?? '',
			"country_code" => $validated['country'],
			"updated_at" => date('Y-m-d H:i:s', $createdResponse['lastModifiedAt'] / 1000),
			"created_at" => date('Y-m-d H:i:s', $createdResponse['createdAt'] / 1000),
		]);

		$app->products()->sync($request->products);

		if ($request->ajax()) {
			return response()->json(['response' => $createdResponse]);
		}

		return redirect(route('app.index'));
	}

	public function edit(ProductLocationService $productLocationService, App $app, Request $request)
	{
		[$products, $countries] = $productLocationService->fetch();

		$app->load('products', 'country');

		return view('templates.apps.edit', [
			'products' => $products,
			'countries' => $countries ?? '',
			'data' => $app,
			'selectedProducts' => array_column($app['products']->toArray(), 'name'),
		]);
	}

	public function update(App $app, CreateAppRequest $request)
	{
		$validated = $request->validated();

		$data = [
			'name' => $validated['name'],
			'key' => $this->getCredentials($app, 'consumerKey', 'string'),
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
			'country_code' => $validated['country'],
		]);

		$app->products()->sync($data['apiProducts']);

		if ($request->ajax()) {
			return response()->json(['response' => $updatedResponse]);
		}

		return redirect(route('app.index'));
	}

	public function destroy(App $app, DeleteAppRequest $request)
	{
		$validated = $request->validated();

		$user = \Auth::user();
		ApigeeService::delete("developers/{$user->email}/apps/{$validated['name']}");

		$app->delete();

		return redirect(route('app.index'));
	}

	/**
	 * Gets the credentials.
	 *
	 * @param      \App\App                       $app    The application
	 * @param      string                         $type   The type
	 *
	 * @return     string|\Illuminate\Http\JsonResponse  The credentials.
	 */
	public function getCredentials(App $app, $type, $respondWith = 'jsonResponse')
	{
		$credentials = ApigeeService::get('apps/' . $app->aid)['credentials'];
		$credentials = ApigeeService::sortCredentials($credentials);
		$typeAndEnvironment = explode('-', $type);

		if ($typeAndEnvironment[1] === 'production') {
			$credentials =  end($credentials)[$typeAndEnvironment[0]];
		} else if ($type !== 'all') {
			$credentials = $credentials[0][$typeAndEnvironment[0]];
		}

		if ($respondWith === 'string') {
			return $credentials;
		}

		return response()->json([
			'credentials' => $credentials
		]);
	}

	/**
	 * Go live process
	 *
	 * @param      \App\App                                                          $app         The application
	 * @param      \App\Services\KycService                                          $kycService  The kyc service
	 *
	 * @return     \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse  Redirect to the correct kyc form
	 */
	public function goLive(App $app, KycService $kycService)
	{
		$app->load('products');
		$groups = $app->products->pluck('group')->toArray();
		$firstKycMethod = $kycService->getFirstKyc($groups);

		if ($firstKycMethod === "") {
			$app->update([
				'live_at' => date('Y-m-d H:i:s')
			]);

			return redirect()->back()->with('alert', "success:{$app->display_name} is now live.");
		}

		$firstKycProcess = $kycService->$firstKycMethod($app);

		return redirect($firstKycProcess['route']);
	}

	/**
	 * Show the kyc form
	 *
	 * @param      \App\App                                                  $app         The application
	 * @param      string                                                    $group       The group
	 * @param      \App\Services\KycService                                  $kycService  The kyc service
	 *
	 * @return     \Illuminate\View\View|\Illuminate\Contracts\View\Factory  Show the form
	 */
	public function kyc(App $app, $group, KycService $kycService)
	{
		$app->load('country');
		$kyc = $kycService->$group($app);

		return view($kyc['view'])->with($kyc['with']);
	}

	/**
	 * Direct to the store mechanism for this kyc
	 *
	 * @param      \App\App                  $app      The application
	 * @param      string                    $group    The group
	 * @param      \Illuminate\Http\Request  $request  The request
	 *
	 * @return     method                    The method to store the form details
	 */
	public function kycStore(App $app, $group, Request $request)
	{
		$kycStoreMethod = "{$group}KycStore";
		return $this->$kycStoreMethod($app, $group, $request);
	}

	/**
	 * The MoMo kyc store method
	 *
	 * @param      \App\App                                                          $app      The application
	 * @param      string                                                            $group    The group
	 * @param      \Illuminate\Http\Request                                          $request  The request
	 *
	 * @return     \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse  Redirect to app index
	 */
	protected function momoKycStore($app, $group, Request $request)
	{
		$data = $request->validate([
			'name' => 'required',
			'national_id' => 'required',
			'number' => 'required',
			'email' => 'required',
			'business_name' => 'required',
			'business_type' => 'required',
			'business_description' => 'required',
			'files' => 'required',
			'accept' => 'required',
		]);

		$app->load([
			'products' => fn ($query) => $query->where('group', $group),
			'country' => fn ($query) => $query->with('opcoUser')
		]);

		$files = $request->file('files');
		$data['files'] = [];
		$data['app'] = $app;
		$data['group'] = $group;

		if (!isset($files[$data['business_type']])) {
			return back()->withErrors('Please add all the files requested.')->withInput();
		}

		$fileName = 'signed-contracting-requirements.pdf';
		$files['Signed Contracting Requirements']->storeAs("kyc/{$app->aid}", $fileName, 'local');
		$data['files'][] = "kyc/{$app->aid}/$fileName";

		foreach ($files[$data['business_type']] as $name => $file) {
			if (!$file) {
				return back()->withErrors('Please add all the files requested.')->withInput();
			}

			$fileName = Str::slug(substr($name, 0, 32)) . '.pdf';
			$file->storeAs("kyc/{$app->aid}", $fileName, 'local');
			$data['files'][] = "kyc/{$app->aid}/$fileName";
		}

		$opcoUserEmails = $app->country->opcoUser->pluck('email')->toArray();
		if (empty($opcoUserEmails)) {
			$opcoUserEmails = env('MAIL_TO_ADDRESS');
		}
		Mail::to($opcoUserEmails)->send(new GoLiveMail($data));

		$app->update([
			'live_at' => date('Y-m-d H:i:s')
		]);

		return redirect()->route('app.index')->with('alert', 'info:Your app is in review;You will get an email about the progress.');
	}
}
