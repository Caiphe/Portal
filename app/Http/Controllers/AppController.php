<?php

namespace App\Http\Controllers;

use App\Country;
use App\Product;
use App\Services\ApigeeService;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        $apps = ApigeeService::get('developers/wes@plusnarrative.com/apps/?expand=true');

        $approved_apps = [];
        $revoked_apps = [];

        foreach ($apps['app'] as $app) {
            if($app['status'] === 'approved') {
                $approved_apps[] = $app;
            } else {
                $revoked_apps[] = $app;
            }
        }

        return view('apps.index', [
            'approved_apps' => $approved_apps,
            'revoked_apps' => $revoked_apps
        ]);
    }

    public function create()
    {
        $products = Product::all()
            ->sortBy('category')
            ->groupBy('category');

        $locations = $products->flatMap(function ($query) {
            return $query->pluck('locations');
        })->reject(function ($location) {
            return empty($location);
        })->unique()->all();

        $regions = collect($locations)->map(function ($query) {
            return explode(',', $query);
        })->values()->all();

//        $temp = array_unique(array_column($regions, 'name'));
//        $unique_arr = array_intersect_key($regions, $temp);
//
//        dd($regions);

//        $input = array_map('unserialize', array_unique(array_map('serialize', $regions)));

//        dd($regions);

//        foreach($regions as $category) {
//            $regions[] = $category['locations'];
//        }

//        $input = array_map("unserialize",
//            array_unique(array_map("serialize", $regions)));
//        dd($input);

        $countries = Country::all();

        $country_array = [];
        foreach ($countries as $country) {
            $country_array[$country->code] = $country->name;
        }

        return view('apps.create', [
                'products' => $products,
                'productCategories' => array_keys($products->toArray()),
                'countries' => $country_array
            ]
        );
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Request $request)
    {
        return view('apps.show');
    }

    public function edit(Request $request)
    {
        $app = '';

        return view('apps.edit', [
            'app' => $app
        ]);
    }

    public function update(Request $request)
    {
        //
    }

    public function destroy()
    {
        //
    }
}
