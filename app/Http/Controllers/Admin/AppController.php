<?php

namespace App\Http\Controllers\Admin;

use App\App;
use App\Product;
use App\Notification;
use App\Services\ApigeeService;
use App\Http\Controllers\Controller;

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

        if ($resp->failed()) {
            $reasonMsg = $resp['message'] ?? 'There was a problem updating the app. Please try again later.';

            return redirect()->back()->with('alert', "error:{$reasonMsg}");
        }

        $resp = $resp->json();

        $app->update([
            'attributes' => $data['attributes'],
            'credentials' => $resp['credentials']
        ]);

        $app->products()->sync($updatedApiProducts);
        
        if($app->team){
            $appUsers = $app->team->users->pluck('id')->toArray();
            foreach($appUsers as $user){
                Notification::create([
                    'user_id' => $user,
                    'notification' => "Your App <strong>{$app->display_name}</strong> from your team {$app->team->name} has been updated. Please navigate to your <a href='/apps'>apps</a> to view the changes.",
                ]);
            }
        }

        if($app->developer){
            Notification::create([
                'user_id' => $app->developer->id,
                'notification' => "Your App {$app->display_name} has been updated. Please navigate to your <a href='/apps'>apps</a> to view the changes",
            ]);
        }

        return redirect()->route('admin.dashboard.index')->with('alert', "success:App has been approved");
    }
}
