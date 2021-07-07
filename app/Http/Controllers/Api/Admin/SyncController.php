<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

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
}
