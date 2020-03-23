<?php

use App\Product;
use App\Services\ApigeeService;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = ApigeeService::get('apiproducts?expand=true')['apiProduct'];

        $attributes = [];

        foreach ($products as $product) {
            $attributes = ApigeeService::getAppAttributes($product['attributes']);

            Product::create([
                'name' => $product['name'],
                'display_name' => $product['displayName'],
                'description' => $product['description'],
                'environments' => implode(',', $product['environments']),
                'group' => $attributes['group'] ?? "MTN",
                'category' => $attributes['category'] ?? "Misc",
                'access' => $attributes['access'] ?? null,
                'locations' => $attributes['locations'] ?? null,
                'swagger' => $attributes['swagger'] ?? null,
            ]);
        }
    }
}
