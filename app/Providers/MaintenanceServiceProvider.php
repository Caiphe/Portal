<?php

namespace App\Providers;

use App\Maintenance;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class MaintenanceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['components.banner'], function ($view) {
            $maintenanceData = Maintenance::where('enabled', 1)->latest()->first();
            
            $view->with('maintenanceData', $maintenanceData);
        });
    }
}
