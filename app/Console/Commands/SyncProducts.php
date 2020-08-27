<?php

namespace App\Console\Commands;

use App\Product;
use App\Category;
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

		$allow = env('APIGEE_ALLOW_PREFIX');
		$deny = explode(',', env('APIGEE_DENY_PREFIX'));

		$products = ApigeeService::get('apiproducts?expand=true')['apiProduct'];

		$attributes = [];

		$this->info("Start syncing products");
		foreach ($products as $product) {
			if ($allow !== "" && strpos($product['displayName'], $allow) === false) continue;
			if (str_replace($deny, '', $product['displayName']) !== $product['displayName']) continue;

			$this->info("Syncing {$product['displayName']}");

			$attributes = ApigeeService::getAppAttributes($product['attributes']);
			$category = Category::firstOrCreate([
				'title' => ucfirst(strtolower(trim($attributes['Category'] ?? "Misc")))
			]);

			Product::withTrashed()->updateOrCreate(
				['pid' => $product['name']],
				[
					'pid' => $product['name'],
					'name' => $product['name'],
					'display_name' => preg_replace('/[-_]+/', ' ', ltrim($product['displayName'], "$allow ")),
					'description' => $product['description'],
					'environments' => implode(',', $product['environments']),
					'group' => $attributes['Group'] ?? "MTN",
					'category_cid' => strtolower($category->cid),
					'access' => $attributes['Access'] ?? null,
					'locations' => $attributes['Locations'] ?? null,
					'swagger' => $attributes['Swagger'] ?? null,
					'attributes' => json_encode($attributes),
				]
			);
		}
	}
}
