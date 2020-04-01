<?php

namespace App\Http\Controllers;

use App\Services\ApigeeService;
use App\Services\ProductLocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photos.profile' => 'required|image',
        ]);

        ApigeeService::updateProductStatus($request->email, $request->app_name, $request->key, $request->product_name, $request->action);

        return redirect()->back();
    }

    /**
     * @param array $apigeeApps
     * @param array $approvedApps
     * @return array
     */
    public function apps(array $apigeeApps, array $approvedApps): array
    {
        foreach ($apigeeApps['app'] as $key => $app) {
            $approvedApps[] = $app;
            $approvedApps[$key]['createdAt'] = date('d M Y H:i:s', $app['createdAt'] / 1000);
        }
        return $approvedApps;
    }
}
