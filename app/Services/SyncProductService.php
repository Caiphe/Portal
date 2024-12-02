<?php 
    namespace App\Services;

    use App\Product;
    use App\Category;
    use App\Country;
    use App\Log;
    use App\User;
    use App\Services\ApigeeService;

    class SyncProductService
    {

        public static function syncProducts()
        {
            $allow = config('apigee.apigee_allow_prefix');
            $deny = explode(',', config('apigee.apigee_deny_prefix'));
            $products = ApigeeService::get('apiproducts?expand=true')['apiProduct'];
            $attributes = [];
            $sandboxProductAttribute = [];
            $allCountries = Country::all();
            $productSyncResult = [];
            $totalProductsAdded = 0;
    
            $productsToBeDeleted = Product::whereNull('deleted_at')->pluck('display_name', 'name')->toArray();

            foreach ($products as $product) {
                if ($allow !== "" && strpos($product['displayName'], $allow) === false) continue;
                if (str_replace($deny, '', $product['displayName']) !== $product['displayName']) continue;
                
                $prod = Product::withTrashed()->find($product['name']);
    
                $attributes = ApigeeService::getProductAttributes($product['attributes']);
    
                if (isset($attributes['SandboxProduct'])) {
                    $sandboxProductAttribute[$attributes['SandboxProduct']] = $product['name'];
                }
    
                $productEnvironments = array_map(function ($env) {
                    $lookup = [
                        'dev' => 'prod',
                        'test' => 'sandbox',
                        'preprod' => 'prod',
                        'qa' => 'qa',
                    ];
    
                    return $lookup[$env] ?? $env;
                }, $product['environments']);
    
                if (isset($productsToBeDeleted[$product['name']])) {
                    unset($productsToBeDeleted[$product['name']]);
                }
    
                $category = Category::firstOrCreate([
                    'title' => ucfirst(strtolower(trim($attributes['Category'] ?? "Misc")))
                ]);
    
                if (!is_null($prod)) {
                    $prod->update([
                        'pid' => $product['name'],
                        'name' => $product['name'],
                        'display_name' => preg_replace('/[_]+/', ' ', ltrim($product['displayName'], "$allow ")),
                        'description' => $product['description'],
                        'environments' => implode(',', $productEnvironments),
                        'category_cid' => strtolower($category->cid),
                        'access' => $attributes['Access'] ?? null,
                        'group' => $attributes['Group'] ?? "MTN",
                        'locations' => $attributes['Locations'] ?? null,
                        'attributes' => json_encode($attributes),
                    ]);
    
                    if (isset($attributes['Locations'])) {
                        $locations = $attributes['Locations'] !== 'all' ? preg_split('/, ?/', $attributes['Locations']) : $allCountries;
                        $prod->countries()->sync($locations);
                    }
    
                    continue;
                }
    
                $prod = Product::create(
                    [
                        'pid' => $product['name'],
                        'name' => $product['name'],
                        'display_name' => preg_replace('/[_]+/', ' ', ltrim($product['displayName'], "$allow ")),
                        'description' => $product['description'],
                        'environments' => implode(',', $productEnvironments),
                        'group' => $attributes['Group'] ?? "MTN",
                        'category_cid' => strtolower($category->cid),
                        'access' => $attributes['Access'] ?? null,
                        'locations' => $attributes['Locations'] ?? null,
                        'swagger' => $attributes['Swagger'] ?? null,
                        'attributes' => json_encode($attributes),
                    ]
                );

                $totalProductsAdded++;
    
                if (isset($attributes['Locations'])) {
                    $locations = $attributes['Locations'] !== 'all' ? preg_split('/, ?/', $attributes['Locations']) : $allCountries;
                    $prod->countries()->sync($locations);
                }
            }
    
            $productSyncResult['deletedProducts'] = count($productsToBeDeleted);
            $productSyncResult['totalProducts'] = count($products);
            $productSyncResult['addedProducts'] = $totalProductsAdded;

            if (count($productsToBeDeleted) > 0) {
                $toDeleProduct = Product::whereIn('pid', array_keys($productsToBeDeleted))->pluck('name')->toArray();
    
                $admin = User::whereHas('roles', fn ($q) => $q->where('name', 'Admin'))->first();

                foreach($toDeleProduct as $prod){
                    Log::create([
                        'user_id' => $admin->id,
                        'logable_id' =>  $prod,
                        'logable_type' => 'App\Product',
                        'message' => "Product name {$prod} has been deleted",
                    ]);
                }
    
                Product::whereIn('pid', array_keys($productsToBeDeleted))->forceDelete();
            }
    
            Product::whereIn('name', array_keys($sandboxProductAttribute))->get()->each(function ($product) use ($sandboxProductAttribute) {
                $attr = json_decode($product->attributes, true);
                $attr['ProductionProduct'] = $sandboxProductAttribute[$product->name];
                $product->update([
                    'attributes' => $attr
                ]);
            });

            return $productSyncResult;
        }
    }
