<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStatusRequest;
use App\Services\ApigeeService;
use App\Services\ProductLocationService;

class DashboardController extends Controller
{
    public function index(ProductLocationService $productLocationService)
    {
        $approvedApps = $this->apps(ApigeeService::getOrgApps('10', 'approved'), $approvedApps = []);
        $revokedApps = $this->apps(ApigeeService::getOrgApps('10', 'revoked'), $revokedApps = []);

        [$countries] = $productLocationService->fetch();

        return view('templates.dashboard.index', [
            'approvedApps' => $approvedApps ?? [],
            'revokedApps' => $revokedApps ?? [],
            'countries' => $countries
        ]);
    }

    public function update(UpdateStatusRequest $request)
    {
        $validated = $request->validated();

         ApigeeService::updateProductStatus($request->email, $request->app_name, $request->key, $request->product_name, $request->action);
    }

    /**
     * @param array $apigeeApps
     * @param array $outputArray
     */
    public function apps(array $apigeeApps, array $outputArray)
    {
        foreach ($apigeeApps['app'] as $key => $app) {
            $outputArray[] = $app;
            $outputArray[$key]['createdAt'] = date('d M Y', substr($app['createdAt'], 0, 10));
        }

        return $outputArray;
    }
}
