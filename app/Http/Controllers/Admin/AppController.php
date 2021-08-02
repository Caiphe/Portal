<?php

namespace App\Http\Controllers\Admin;

use App\App;
use App\Http\Controllers\Controller;
use App\Product;
use App\Services\ApigeeService;

class AppController extends Controller
{
    public function approve(App $app)
    {
        $app->load(['products', 'country']);
        $currentUser = \Auth::user();
        $updatedApiProducts = [];
        $apiProducts = [];
        $now = date('Y-m-d H:i:s');
        $attr = ['status' => 'approved', 'actioned_by' => $currentUser->id, 'live_at' => $now];

        foreach ($app->products as $product) {
            $findProduct = Product::whereName("{$product->name}-prod")->first();
            $updatedApiProducts[$product->name] = $attr;
            if (is_null($findProduct)) {
                $apiProducts[] = $product->name;
                continue;
            }
            $updatedApiProducts[$findProduct->name] = $attr;
            $apiProducts[] = $findProduct->name;
        }

        $data = [
            'name' => $app['name'],
            'apiProducts' => $apiProducts,
            'callbackUrl' => $app['callback_url'],
            'attributes' => [
                [
                    'name' => 'DisplayName',
                    'value' => $app['display_name'],
                ],
                [
                    'name' => 'Description',
                    'value' => $app['description'],
                ],
                [
                    'name' => 'Country',
                    'value' => $app->country->code,
                ],
                [
                    'name' => 'location',
                    'value' => $app->country->iso,
                ],
                [
                    'name' => 'ApprovedAt',
                    'value' => $now,
                ],
            ]
        ];
        $resp = ApigeeService::updateAppWithNewCredentials($data);
        $status = $resp->status();
        $resp = $resp->json();
        if($status !== 200 && $status !== 201){
            return redirect()->back()->with('alert', "error:{$resp['message']}");
        }

        $app->update([
            'attributes' => $data['attributes'],
            'credentials' => $resp['credentials']
        ]);

        $app->products()->sync($updatedApiProducts);

        return redirect()->route('admin.dashboard.index')->with('alert', "success:App has been approved");
    }
}
