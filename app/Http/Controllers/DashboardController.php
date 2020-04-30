<?php

namespace App\Http\Controllers;

use App\App;
use App\Http\Requests\UpdateStatusRequest;
use App\Product;
use App\Services\ApigeeService;
use App\Services\ProductLocationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DashboardController extends Controller {

	public function index(ProductLocationService $productLocationService) {
		$user = auth()->user();
		$user->load('responsibleCountries');
		$isAdmin = $user->hasRole('admin');

		$apigeeDevelopers = [];
		foreach (ApigeeService::get('developers?expand=true')['developer'] as $developer) {
			$apigeeDevelopers[$developer['developerId']] = [
				'first_name' => $developer['firstName'],
				'last_name' => $developer['lastName'],
				'email' => $developer['email'],
				'developer_id' => $developer['developerId'],
			];
		}

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
					$app['developer'] = $apigeeDevelopers[$app['developer_id']];
					$app['countries'] = $appCountries;
					$approvedApps[] = $app;
				};

				continue;
			}

			$app['developer'] = $apigeeDevelopers[$app['developer_id']];
			$app['countries'] = $appCountries;
			$approvedApps[] = $app;
		}

		[$products, $countries] = $productLocationService->fetch();

		return view('templates.dashboard.index', [
			'approvedApps' => $approvedApps,
			'countries' => $countries,
		]);
	}

	public function update(UpdateStatusRequest $request) {
		$validated = $request->validated();

		ApigeeService::updateProductStatus($request->developer_id, $request->app_name, $request->key, $request->product, $request->action);

		return redirect()->back();
	}

	public function destroy($id, Request $request) {
		dd($request->all());
	}
}
