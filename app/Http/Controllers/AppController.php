<?php

namespace App\Http\Controllers;

use App\Country;
use App\Http\Requests\CreateAppRequest;
use App\Http\Requests\DeleteAppRequest;
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

        $countries = $products->flatMap(function ($query) {
            return $query->pluck('locations');
        })->reject(function ($location) {
            return empty($location);
        })->unique()->values()->all();

        $validCountries = collect($countries)->mapWithKeys(function ($query) {
            return explode(',', $query);
        })->unique();

        $filteredCountries = Country::whereIn('code', $validCountries)->get();

        $countries = $filteredCountries->map(function ($query) {
            return $query;
        })->pluck('name', 'code');

        $productLocations = Product::isPublic()->WhereNotNull('locations') ->select('locations') ->get() ->implode('locations', ',');
        array_unique(explode(',', $productLocations));
        dd($productLocations);

        return view('apps.create', [
                'products' => $products,
                'productCategories' => array_keys($products->toArray()),
                'countries' => $countries
            ]
        );
    }

    public function store(Request $request)
    {
        // $validated = $request->validated();
//        {
//            "name" : "my_app_internal_name",
//             "apiProducts": [ "api_product_1", "api_product_2" ],
//             "keyExpiresIn" : 2592000000,
//             "attributes" : [
//              {
//                  "name" : "DisplayName",
//               "value" : "My Awesome App"
//              },
//              {
//                  "name" : "Notes",
//               "value" : "notes_for_developer_app"
//              },
//              {
//                  "name" : "custom_attribute_name",
//               "value" : "custom_attribute_value"
//              }
//             ],
//             "scopes" : [ "scope_a" ],
//             "callbackUrl" : "https://url-for-3-legged-oauth/"
//        }
        dd($request->all());

        // ApigeeService::createApp($request->all());

        return response([
            'message' => 'App created successfully'
        ], 201);
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

    public function destroy(DeleteAppRequest $request)
    {
        $validated = $request->validated();

        ApigeeService::delete("developers/wes@plusnarrative.com/apps/{$validated['name']}");

        return redirect()->back()->with('status', 'App has been deleted');
    }
}
