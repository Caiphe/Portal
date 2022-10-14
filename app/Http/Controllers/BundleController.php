<?php

namespace App\Http\Controllers;

use App\Bundle;
use App\Content;
use App\Services\ProductLocationService;

class BundleController extends Controller
{
    /**
     * Show an individual bundle.
     * @param  \App\Bundle $bundle      The bundle that is being requested
     * @return \Illuminate\View\View    Show the view
     */
    public function show(Bundle $bundle, ProductLocationService $productLocationService)
    {
        $bundle->load(['products', 'content', 'keyFeatures']);
        $bundles = Bundle::with('products')->get();
        $sidebar = $bundles->map(function($bundle){
            return ['name' => $bundle->display_name, 'slug' => $bundle->slug];
        });

        return view('templates.bundles.show', [
            'bundle' => $bundle,
            'content' => $bundle->content->groupBy('type'),
            'sidebar' => $sidebar,
            'countries' => $productLocationService->fetch($bundle->products->pluck('pid')->toArray(), 'countries')
        ]);
    }
}
