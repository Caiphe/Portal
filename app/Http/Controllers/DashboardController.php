<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $approvedApps = [];
        $revokedApps = [];

        return view('templates.dashboard.index', [
            'approvedApps' => $approvedApps ?? [],
            'revokedApps' => $revokedApps ?? []
        ]);
    }
}
