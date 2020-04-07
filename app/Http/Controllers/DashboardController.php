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
            'approvedApps' => [],
            'revokedApps' => [],
            'countries' => $countries
        ]);
    }

    public function approve(Request $request)
    {
        dd($request->input('name'));

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
