<?php

namespace App\Http\Controllers;

use App\Services\ApigeeService;
use App\Services\ProductLocationService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(ProductLocationService $productLocationService)
    {
        $approvedApps = ApigeeService::get('/apps?rows=10&expand=true&status=approved');
        $revokedApps = ApigeeService::get('/apps?rows=10&expand=true&status=revoked');

        [$countries] = $productLocationService->fetch();

        return view('templates.dashboard.index', [
            'approvedApps' => $approvedApps['app'] ?? [],
            'revokedApps' => $revokedApps['app'] ?? [],
            'countries' => $countries
        ]);
    }

    public function approve(Request $request)
    {
        dd($request->all());

//        ApigeeService::post('', ['action' => $request['action']]);
//        developers/$request['email']/apps/$request['app_name']/$request['consumer_key']/apiproducts/$request['productName']
//        $responseInfo = postToApigee(
//                “developers/{$_POST[‘user_email’]}/apps/{$_POST[‘app_name’]}/keys/{$_POST[‘consumer_key’]}/apiproducts/{$productName}“,
//[
//    “action” => $_POST[‘action’]
//],
//‘query’
//);

        return redirect()->back();
    }

    public function revoke(Request $request)
    {

        return redirect()->back();
    }

    public function approveAll(Request $request)
    {

        return redirect()->back();
    }

    public function revokeAll(Request $request)
    {

        return redirect()->back();
    }

    public function complete(Request $request)
    {

        return redirect()->back();
    }
}
