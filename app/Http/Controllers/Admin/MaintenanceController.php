<?php

namespace App\Http\Controllers\Admin;

use App\Maintenance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaintenanceBannerRequest;

class MaintenanceController extends Controller
{
    public function create()
    {
        $maintenanceData = Maintenance::latest()->first();

        return view('templates.admin.settings.maintenance', [
            'maintenanceData' => $maintenanceData
        ]);
    }

    public function store(MaintenanceBannerRequest $request)
    {
        $data = $request->validated();

        $enabled = $data['enabled'] === 'enabled' ? true : false;
        $created_by = auth()->user()->full_name;

        // Setting all the previous maintenance to disabled before creating a new one
        $existingMaintenance = Maintenance::all();
        if($existingMaintenance->count() > 0) {
            $existingMaintenance->each(function($maintenance) {
                $maintenance->update(['enabled' => false]);
            });
        }

        $maintenance = Maintenance::create([
            'created_by' => $created_by,
            'message' => $data['message'],
            'enabled' => $enabled
        ]);

        if($maintenance) {
            return response()->json(['success' => true, 'code' => 200], 200);
        }
    }
}
