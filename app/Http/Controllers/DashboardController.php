<?php

namespace App\Http\Controllers;

use App\App;
use App\Http\Requests\UpdateStatusRequest;
use App\Services\ApigeeService;
use App\Services\ProductLocationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

	public function index(ProductLocationService $productLocationService)
	{
		$user = auth()->user();
		$user->load('responsibleCountries');
		$isAdmin = $user->hasRole('admin');

		$apps = App::with(['developer', 'products', 'country'])
			->whereHas('products', function (Builder $query) use ($user, $isAdmin) {
				$q = $query
					->whereNotNull('swagger')
					->where('status', 'pending');
				if (!$isAdmin) {
					$responsibleCountriesCode = $user->responsibleCountries->pluck('code')->implode('|');
					$q->whereRaw("CONCAT(\",\", `locations`, \",\") REGEXP \",(" . $responsibleCountriesCode . "),\"");
				}
			})
			->whereHas('developer')
			->byStatus('approved')
			->orderBy('updated_at', 'desc')
			->get()
			->toArray();

		$approvedApps = [];
		$responsibleCountries = $user->responsibleCountries->pluck('code')->toArray();
		foreach ($apps as $app) {
			$appCountries = ApigeeService::getAppCountries(array_column($app['products'], 'name'));
			if (!$isAdmin) {
				$appCountriesCodes = array_keys($appCountries);
				if (count(array_intersect($responsibleCountries, $appCountriesCodes)) > 0) {
					$app['countries'] = $appCountries;
					$approvedApps[] = $app;
				};

				continue;
			}

			$app['countries'] = $appCountries;
			$approvedApps[] = $app;
		}

		$countries = $productLocationService->fetch([], 'countries');
		$countries['all'] = "Global";
		$countries['mix'] = "Mixed";

		return view('templates.dashboard.index', [
			'approvedApps' => $approvedApps,
			'countries' => $countries,
		]);
	}

	public function update(UpdateStatusRequest $request)
	{
		$validated = $request->validated();
		$app = App::with('developer')->where('name', $validated['app'])->first();
		$status = [
			'approve' => 'approved',
			'revoke' => 'revoked',
			'pending' => 'pending'
		][$validated['action']] ?? 'pending';

		$credentials = ApigeeService::get('apps/' . $app->aid)['credentials'];
		$credentials = ApigeeService::getLatestCredentials($credentials);

		$response = ApigeeService::updateProductStatus($app->developer->email, $validated['app'], $credentials['consumerKey'], $validated['product'], $validated['action']);
		$responseStatus = $response->status();
		if ($responseStatus === 200) {
			$app->products()->updateExistingPivot($validated['product'], ['status' => $status, 'actioned_by' => $request->user()->id]);
		} else if ($request->ajax()) {
			return response()->json(['success' => false, 'body' => json_decode($response->body())], $responseStatus);
		}

		if ($request->ajax()) {
			return response()->json(['success' => true]);
		}

		return redirect()->back();
	}

	public function destroy($id, Request $request)
	{
		dd($request->all());
	}
}
