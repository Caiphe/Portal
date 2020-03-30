<?php

namespace App\Http\Controllers;

use App\Country;
use App\Http\Requests\CreateAppRequest;
use App\Http\Requests\DeleteAppRequest;
use App\Product;
use App\Services\ApigeeService;
use App\Services\ProductLocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function index()
    {
        $apps = ApigeeService::get('developers/wes@plusnarrative.com/apps/?expand=true');

        $approvedApps = [];
        $revokedApps = [];

        foreach ($apps['app'] as $app) {
            if($app['status'] === 'approved') {
                $approvedApps[] = $app;
            } else {
                $revokedApps[] = $app;
            }
        }

        return view('templates.apps.index', [
            'approvedApps' => $approvedApps,
            'revokedApps' => $revokedApps
        ]);
    }

    public function create(ProductLocationService $productLocationService)
    {
        [$products, $countries] = $productLocationService->fetch();

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

        // TODO: REMOVE LOGIN
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

        return redirect(route('app.index'))->with('status', 'Application created successfully');
    }

    public function edit(ProductLocationService $productLocationService, Request $request)
    {
        [$products, $countries] = $productLocationService->fetch();

        $data = ApigeeService::get("developers/wes@plusnarrative.com/apps/{$request->name}/?expand=true");

        $selectedProducts = array_column(end($data['credentials'])['apiProducts'], 'apiproduct');

        return view('templates.apps.edit', [
            'products' => $products,
            'productCategories' => array_keys($products->toArray()),
            'countries' => $countries ?? '',
            'data' => $data,
            'selectedProducts' => $selectedProducts
        ]);
    }

    public function update(CreateAppRequest $request)
    {
        $validated = $request->validated();

        // TODO: REMOVE LOGIN
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

        ApigeeService::updateApp($data);

        return redirect(route('app.index'))->with('status', 'Application updated successfully');
    }

    public function destroy(DeleteAppRequest $request)
    {
        $validated = $request->validated();

        ApigeeService::delete("developers/wes@plusnarrative.com/apps/{$validated['name']}");

        return redirect(route('app.index'))->with('status', 'App has been deleted');
    }
}
