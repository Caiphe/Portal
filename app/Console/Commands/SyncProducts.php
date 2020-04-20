<?php

namespace App\Console\Commands;

use App\Product;
use App\Services\ApigeeService;
use Illuminate\Console\Command;

class SyncProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the products from Apigee';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Getting products from Apigee");
        $products = ApigeeService::get('apiproducts?expand=true')['apiProduct'];

        $attributes = [];

        $this->info("Start syncing products");
        foreach ($products as $product) {
            $this->info("Syncing {$product['displayName']}");

            $attributes = ApigeeService::getAppAttributes($product['attributes']);

            Product::updateOrCreate(
                ['pid' => $product['name']],
                [
                    'pid' => $product['name'],
                    'name' => $product['name'],
                    'display_name' => $product['displayName'],
                    'description' => $product['description'],
                    'environments' => implode(',', $product['environments']),
                    'group' => $attributes['group'] ?? "MTN",
                    'category' => $attributes['category'] ?? "Misc",
                    'access' => $attributes['access'] ?? null,
                    'locations' => $attributes['locations'] ?? null,
                    'swagger' => $attributes['swagger'] ?? null,
                    'attributes' => json_encode($attributes)
                ]
            );
        }
    }
}
