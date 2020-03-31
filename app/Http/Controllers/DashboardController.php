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
        return redirect()->back()->with('alert', 'Success');
    }

    public function revoke()
    {

    }
}
