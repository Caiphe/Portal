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

        Auth::loginUsingId(1);

        $apiProducts = Product::findMany($request->products[0])->pluck('name')->toArray();

        $data = [
            'name' => strtolower(str_replace(' ', '-', $validated['name'])),
            'apiProducts' => $apiProducts,
            'keyExpiresIn' => -1,
            'attributes' => [
                [
                    'name' => 'DisplayName',
                    'value' => $validated['name']
                ],
                [
                    'name' => 'Description',
                    'value' => preg_replace('/[<>"]*/', '', strip_tags($validated['description']))
                ]
            ],
            'callbackUrl' => preg_replace('/[<>"]*/', '', strip_tags($validated['url'])) ?? ''
        ];

        ApigeeService::createApp($data);

        return redirect('apps.index')->with('status', 'Application created successfully');
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

        dd($request->all());

        ApigeeService::delete("developers/wes@plusnarrative.com/apps/{$validated['name']}");

        return redirect()->back()->with('status', 'App has been deleted');
    }
}
