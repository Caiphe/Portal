<?php

namespace App\Console\Commands;

use App\App;
use App\Product;
use App\Country;
use App\Services\ApigeeService;
use Illuminate\Console\Command;

class SyncApps extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'sync:apps';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sync the apps from Apigee';

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
		$this->info("Getting apps from Apigee");
		$apps = ApigeeService::getOrgApps('all', 0);
		$countries = Country::all();
		$countriesByCode = $countries->pluck('id', 'code');
		$countriesByName = $countries->pluck('id', 'name');
		$products = Product::pluck('name')->toArray();

		$this->info("Start syncing apps");

		foreach ($apps['app'] as $app) {
			$apiProducts = [];
			foreach ($app['credentials']['apiProducts'] as $product) {
				if (!in_array($product['apiproduct'], $products)) continue 2;
				$apiProducts[$product['apiproduct']] = ['status' => $product['status']];
			}

			$this->info("Syncing {$app['name']}");

			$attributes = ApigeeService::getAppAttributes($app['attributes']);

			unset($app['credentials']['apiProducts']);

			if (isset($attributes['DisplayName']) && !empty($attributes['DisplayName'])) {
				$displayName = $attributes['DisplayName'];
			} else {
				$displayName = $app['name'];
			}

			$countryId = null;
			if (isset($attributes['Country']) && strlen($attributes['Country']) === 2) {
				$countryId = $countriesByCode[$attributes['Country']];
			} else if (isset($attributes['Country']) && isset($countriesByName[$attributes['Country']])) {
				$countryId = $countriesByName[$attributes['Country']];
			}

			$a = App::updateOrCreate(
				["aid" => $app['appId']],
				[
					"aid" => $app['appId'],
					"name" => $app['name'],
					"display_name" => $displayName,
					"callback_url" => $app['callbackUrl'],
					"attributes" => $attributes,
					"credentials" => $app['credentials'],
					"developer_id" => $app['developerId'],
					"status" => $app['status'],
					"description" => $attributes['Description'] ?? '',
					"country_id" => $countryId,
					"updated_at" => date('Y-m-d H:i:s', $app['lastModifiedAt'] / 1000),
					"created_at" => date('Y-m-d H:i:s', $app['createdAt'] / 1000),
				]
			);

			$a->products()->sync($apiProducts);
		}
	}
}
