<?php

namespace App\Http\Controllers;

use App\Country;
use App\Http\Requests\CreateAppRequest;
use App\Http\Requests\DeleteAppRequest;
use App\Product;
use App\Services\ApigeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('templates.apps.index', [
            'approved_apps' => $approved_apps,
            'revoked_apps' => $revoked_apps
        ]);
    }

    public function create()
    {
        $products = Product::all()
            ->sortBy('category')
            ->groupBy('category');

        $productLocations = Product::isPublic()
            ->whereNotNull('locations')
            ->select('locations')
            ->get()
            ->implode('locations', ',');

        $productLocations = array_unique(explode(',', $productLocations));

        $filteredCountries = Country::whereIn('code', $productLocations)->get();

        $countries = $filteredCountries->each(function ($query) {
            return $query;
        })->pluck('name', 'code');

        return view('templates.apps.create', [
                'products' => $products,
                'productCategories' => array_keys($products->toArray()),
                'countries' => $countries ?? ''
            ]
        );
    }

    public function store(CreateAppRequest $request)
    {
         $validated = $request->validated();

         $data = [];

         $data['name'] = strtolower(str_replace(' ', '-', $validated['name']));
         $data['callbackUrl'] = $validated['url'] ?? '';
         $data['attributes'][0]['name'] = 'Description';
         $data['attributes'][0]['value'] = $validated['description'];
         $data['attributes'][1]['name'] = 'DisplayName';
         $data['attributes'][1]['value'] = $validated['name'];
         $data['apiProducts'] = [];
         $data['expiresAt'] = -1;

         $products = Product::findMany(collect($validated['products'])->flatten());
         $products = $products->pluck('name');

//         dd($products);

         $apiProducts = [];

         foreach($products as $product) {
             $apiProducts[] = str_replace(' ', '-', $product);
         }
//
         $data['apiProducts'] = $apiProducts;

         Auth::loginUsingId(1);

         $create = ApigeeService::createApp($data);

         dd($create);

        return redirect(route('app.index'))->with(
            ['message' => 'App created successfully'], 201
        );
    }

    public function show(Request $request)
    {
        return view('templates.apps.show');
    }

    public function edit(Request $request)
    {
        $app = ApigeeService::get("developers/wes@plusnarrative.com/apps/{$request->name}/?expand=true");

        // dd($app);

        return view('templates.apps.edit', [
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
