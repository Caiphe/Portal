<?php

namespace App\Http\Controllers;

use App\Bundle;
use App\Services\ProductLocationService;

class BundleController extends Controller
{
    /**
     * Show all the bundles
     * @return \Illuminate\View\View Show the view
     */
    public function index(ProductLocationService $productLocationService)
    {
        $bundles = Bundle::with(['products', 'category'])->get();

        $products = $bundles->reduce(function($carry, $bundle){
            $carry = array_merge($bundle->products->pluck('pid')->toArray(), $carry);
            return $carry;
        }, []);

        return view('templates.bundles.index', [
            'bundles' => $bundles,
            'categories' => $bundles->pluck('category.title', 'category.cid'),
            'countries' => $productLocationService->fetch($products, 'countries')
        ]);
    }

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