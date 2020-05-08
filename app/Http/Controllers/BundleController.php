<?php

namespace App\Http\Controllers;

use App\Bundle;

class BundleController extends Controller
{
    /**
     * Show all the bundles
     * @return \Illuminate\View\View Show the view
     */
    public function index()
    {
        $bundles = Bundle::with('products')->get();

        return view('templates.bundles.index', [
            'bundles' => $bundles
        ]);
    }

    /**
     * Show an individual bundle.
     * @param  \App\Bundle $bundle      The bundle that is being requested
     * @return \Illuminate\View\View    Show the view
     */
    public function show(Bundle $bundle)
    {

        $bundle->load(['products', 'content']);
        $bundles = Bundle::with('products')->get();

        $sidebar = $bundles->reduce(function($carry, $bundle){
            $carry[$bundle->display_name] = $bundle->products->map(function($product){
                return [ 'label' => $product['display_name'], 'link' => route('product.show', $product['slug'])];
            })->toArray();

            return $carry;
        }, []);

        return view('templates.bundles.show', [
            'bundle' => $bundle,
            'content' => $bundle->content->groupBy('type'),
            'sidebar' => (array)$sidebar
        ]);
    }
}