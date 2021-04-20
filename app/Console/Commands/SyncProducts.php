<?php

namespace App\Console\Commands;

use App\Product;
use App\Category;
use App\Country;
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
		$sandboxProductAttribute = [];
		$allCountries = Country::all();

		$this->info("Start syncing products");
		foreach ($products as $product) {
			if ($allow !== "" && strpos($product['displayName'], $allow) === false) continue;
			if (str_replace($deny, '', $product['displayName']) !== $product['displayName']) continue;

			$this->info("Syncing {$product['displayName']}");

			$prod = Product::withTrashed()->find($product['name']);

			$attributes = ApigeeService::getAppAttributes($product['attributes']);

			if (isset($attributes['SandboxProduct'])) {
				$sandboxProductAttribute[$attributes['SandboxProduct']] = $product['name'];
			}

			$productEnvironments = array_map(function($env){
				$lookup = [
					'dev' => 'prod',
					'test' => 'sandbox'
				];

				return $lookup[$env] ?? $env;
			}, $product['environments']);

			if (!is_null($prod)) {
				$prod->update([
					'pid' => $product['name'],
					'name' => $product['name'],
					'environments' => implode(',', $productEnvironments),
					'access' => $attributes['Access'] ?? null,
					'attributes' => json_encode($attributes),
				]);

				continue;
			}

			$category = Category::firstOrCreate([
				'title' => ucfirst(strtolower(trim($attributes['Category'] ?? "Misc")))
			]);

			$prod = Product::create(
				[
					'pid' => $product['name'],
					'name' => $product['name'],
					'display_name' => preg_replace('/[-_]+/', ' ', ltrim($product['displayName'], "$allow ")),
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

			if (isset($attributes['Locations'])) {
				$locations = $attributes['Locations'] !== 'all' ? explode(',', $attributes['Locations']) : $allCountries;
				$prod->countries()->sync($locations);
			}
		}

		if (empty($sandboxProductAttribute)) return;

		Product::whereIn('name', array_keys($sandboxProductAttribute))->get()->each(function ($product) use ($sandboxProductAttribute) {
			$attr = json_decode($product->attributes, true);
			$attr['ProductionProduct'] = $sandboxProductAttribute[$product->name];
			$product->update([
				'attributes' => $attr
			]);
		});
	}
}
