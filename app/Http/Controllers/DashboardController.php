<?php

namespace App\Http\Controllers;

use App\App;
use App\Http\Requests\UpdateStatusRequest;
use App\Services\ApigeeService;
use App\Services\ProductLocationService;
use Illuminate\Http\Request;

class DashboardController extends Controller {

	public function index(ProductLocationService $productLocationService) {
		$approvedApps = App::with(['developer', 'products', 'country'])
			->byStatus('approved')
			->orderBy('updated_at', 'desc')
			->take(10)
			->get();

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
