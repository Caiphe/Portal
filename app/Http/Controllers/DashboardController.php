<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStatusRequest;
use App\Services\ApigeeService;
use App\Services\ProductLocationService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index(ProductLocationService $productLocationService)
    {
        $approvedApps = $this->apps(ApigeeService::getOrgApps('10', 'approved'), $approvedApps = []);
        $revokedApps = $this->apps(ApigeeService::getOrgApps('10', 'revoked'), $revokedApps = []);

        [$countries] = $productLocationService->fetch();

//        dd($approvedApps);

//        foreach ($approvedApps as $key => $part) {
//            $sort[$key] = strtotime($part['createdAt']);
//        }
//        array_multisort($sort, SORT_DESC, $approvedApps);

//        dd($approvedApps);

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
    protected function apps(array $apigeeApps, array $outputArray)
    {
        foreach ($apigeeApps['app'] as $key => $app) {
            $outputArray[] = $app;
//            $outputArray[$key]['createdAt'] = date('d M Y', $app['createdAt'] / 1000);
        }

        usort($outputArray, function($a, $b) {
            return date('d M Y', $a['createdAt'] / 1000) <=> date('d M Y', $b['createdAt'] / 1000);
        });

//        array_multisort($outputArray, SORT_DESC, $outputArray);

        return $outputArray;
    }
}
