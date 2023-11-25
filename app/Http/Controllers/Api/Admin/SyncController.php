<?php

namespace App\Http\Controllers\Api\Admin;

use App\Jobs\SyncAll;
use App\Mail\SyncNotificationMail;
use App\Http\Controllers\Controller;
use App\Services\SyncProductService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class SyncController extends Controller
{

    /**
     * Sync Products and Bundles from Apigee.
     *
     * @return \Illuminate\Http\Response
     */
    public function sync()
    {
        Artisan::call('sync:products');
        Artisan::call('sync:bundles');
        Artisan::call('sync:apps');

        return response()->json(['success' => true]);
    }

    public function syncProducts()
    {
        Artisan::call('sync:products');
        Artisan::call('sync:bundles');

        return response()->json(['success' => true]);        
    }

    public function syncApps()
    {
        Artisan::call('sync:apps');

        return response()->json(['success' => true]);        
    }

    public function syncData()
    {
        $user = Auth()->user();
        // Sync product to output an array of products deleted updated and created to be emailed to the admin user 
        // Sync App to output an array of apps deleted updated and created to be emailed to the admin user 
        // SyncAll::dispatch($user);
        $syncProductService = new SyncProductService();
        $syncProductService->syncProducts();
        
        return response()->json(['success' => true]);
    }
}
