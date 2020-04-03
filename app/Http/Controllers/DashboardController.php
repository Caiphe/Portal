<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStatusRequest;
use App\Services\ApigeeService;
use App\Services\ProductLocationService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(ProductLocationService $productLocationService)
    {
        $approvedApps = $this->apps(ApigeeService::getOrgApps('approved'), $approvedApps = []);
        $revokedApps = $this->apps(ApigeeService::getOrgApps('revoked'), $revokedApps = []);

//        $developers[] = ApigeeService::getDevelopers();

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

         ApigeeService::updateProductStatus($request->developer_id, $request->app_name, $request->key, $request->product, $request->action);

         return redirect()->back();
    }

    public function destroy($id, Request $request)
    {
        dd($request->all());
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
        }

        usort($outputArray, function($a, $b) {
            return ($a['createdAt'] < $b['createdAt']) ? -1 : 1;
        });

        return array_reverse($outputArray, true);
    }
}
