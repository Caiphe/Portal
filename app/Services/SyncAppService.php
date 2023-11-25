<?php 

    namespace App\Services;

    use App\App;
    use App\Product;
    use App\Country;
    use App\Services\ApigeeService;
    use Exception;

    class SyncAppService
    {

        public static function syncApps(): void
        {
            $countries = Country::get();
            $countryArray = Country::get()->toArray();
            $apps = ApigeeService::getOrgApps('all', 0);
            $products = Product::pluck('name')->toArray();
            $appsToBeDeleted = App::whereNull('deleted_at')->pluck('display_name', 'aid')->toArray();

            foreach ($apps as $app) {
                $apiProducts = [];

                if (count($app['credentials']) === 0) {
                    throw new Exception("No creds: {$app['name']}");
                    continue;
                };

                foreach ($app['credentials'][0]['apiProducts'] as $product) {
                    if (!in_array($product['apiproduct'], $products)) {
                        throw new Exception("No product: {$app['name']}");
                        continue 2;
                    }
                    $apiProducts[$product['apiproduct']] = ['status' => $product['status']];
                }

                if (count($app['credentials']) > 1) {
                    foreach (end($app['credentials'])['apiProducts'] as $product) {
                        if (!in_array($product['apiproduct'], $products)) {
                            throw new Exception("No sandbox product: {$app['name']}");
                            continue 2;
                        }
                        $apiProducts[$product['apiproduct']] = ['status' => $product['status']];
                    }
                }

                throw new Exception("Syncing {$app['name']}");

                $attributes = ApigeeService::formatAppAttributes($app['attributes']);

                if (isset($attributes['DisplayName']) && !empty($attributes['DisplayName'])) {
                    $displayName = $attributes['DisplayName'];
                } else {
                    $displayName = $app['name'];
                }

                $countryCode = null;
                if (isset($attributes['Country']) && is_numeric($attributes['Country'])) {
                    $countryCode = $countryArray[$attributes['Country']]['code'];
                } else if (isset($attributes['Country']) && strlen($attributes['Country']) === 3) {
                    $countryCode = $countries->first(fn ($country) => strtolower($country->iso) === strtolower($attributes['Country']))->code ?? '';
                } else if (isset($attributes['Country']) && strlen($attributes['Country']) > 2) {
                    $countryCode = $countries->first(fn ($country) => $country->name === $attributes['Country'])->code ?? 'all';
                } else if (isset($attributes['Country'])) {
                    $countryCode = $attributes['Country'];
                }

                if (isset($appsToBeDeleted[$app['appId']])) {
                    unset($appsToBeDeleted[$app['appId']]);
                }

                $a = App::withTrashed()->updateOrCreate(
                    ["aid" => $app['appId']],
                    [
                        "aid" => $app['appId'],
                        "name" => $app['name'],
                        "display_name" => $displayName,
                        "callback_url" => $app['callbackUrl'],
                        "attributes" => $attributes,
                        "credentials" => $app['credentials'],
                        "developer_id" => $app['developerId'] ?? $app['createdBy'],
                        "status" => $app['status'],
                        "description" => $attributes['Description'] ?? '',
                        "country_code" => $countryCode,
                        "updated_at" => date('Y-m-d H:i:s', $app['lastModifiedAt'] / 1000),
                        "created_at" => date('Y-m-d H:i:s', $app['createdAt'] / 1000),
                    ]
                );

                $a->products()->sync($apiProducts);
            }

            throw new Exception('Total apps to be deleted: ' . count($appsToBeDeleted));

            if (count($appsToBeDeleted) > 0) {
                App::whereIn('aid', array_keys($appsToBeDeleted))->delete();
            }
        }
    }
    