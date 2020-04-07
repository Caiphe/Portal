<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAppRequest;
use App\Http\Requests\DeleteAppRequest;
use App\Product;
use App\Services\ApigeeService;
use App\Services\ProductLocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        $apps = ApigeeService::getDeveloperApps($user->email);

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
            'approvedApps' => $approvedApps ?? [],
            'revokedApps' => $revokedApps ?? []
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

        $createdResponse = ApigeeService::createApp($data);

        if($request->ajax()){
            return response()->json(['response' => $createdResponse]);
        }

        return redirect(route('app.index'));
    }

    public function edit(ProductLocationService $productLocationService, Request $request)
    {
        [$products, $countries] = $productLocationService->fetch();

        $user = \Auth::user();
        $data = ApigeeService::getDeveloperApp($user->email,$request->name);

        $selectedProducts = array_column($data['credentials']['apiProducts'], 'apiproduct');

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

        $apiProducts = Product::findMany($request->products[0])->pluck('name')->toArray();

        $data = [
            'name' => $validated['name'],
            'key' => $validated['key'],
            'apiProducts' => $apiProducts,
            'originalProducts' => $validated['original_products'],
            'keyExpiresIn' => -1,
            'attributes' => [
                [
                    'name' => 'DisplayName',
                    'value' => $validated['new_name'] ?? $validated['name']
                ],
                [
                    'name' => 'Description',
                    'value' => preg_replace('/[<>"]*/', '', strip_tags($validated['description']))
                ]
            ],
            'callbackUrl' => preg_replace('/[<>"]*/', '', strip_tags($validated['url'])) ?? ''
        ];

        $updatedResponse = ApigeeService::updateApp($data);

        if($request->ajax()){
            return response()->json(['response' => $updatedResponse]);
        }

        return redirect(route('app.index'));
    }

    public function destroy(DeleteAppRequest $request)
    {
        $validated = $request->validated();

        $user = \Auth::user();
        ApigeeService::delete("developers/{$user->email}/apps/{$validated['name']}");

        return redirect(route('app.index'));
    }
}
