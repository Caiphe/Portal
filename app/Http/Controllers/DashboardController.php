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

        [$products, $countries] = $productLocationService->fetch();

        return view('templates.dashboard.index', [
            'approvedApps' => $approvedApps ?? [],
            'revokedApps' => $revokedApps ?? [],
            'countries' => $countries
        ]);
    }

    public function update(UpdateStatusRequest $request)
    {
        $validated = $request->validated();

         ApigeeService::updateProductStatus($request->developer_id, $request->app_name, $request->key, $request->product_name, $request->action);

         return redirect()->back();
    }

    /**
     * @param array $apigeeApps
     * @param array $outputArray
     *
     * @return array
     */
    public function apps(array $apigeeApps, array $outputArray)
    {
        foreach ($apigeeApps['app'] as $key => $app) {
            $outputArray[] = $app;
            $outputArray[$key]['createdAt'] = strtotime($app['createdAt']);
        }

//        usort($outputArray, function($a, $b) {
//            return ($a['createdAt'] < $b['createdAt']) ? -1 : 1;
//        });

        return $outputArray;
    }
}
