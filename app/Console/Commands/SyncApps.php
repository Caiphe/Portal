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
		$countries = Country::get();
		$countryArray = Country::get()->toArray();
		$apps = ApigeeService::getOrgApps('all', 0);
		$products = Product::pluck('name')->toArray();
		$appsToBeDeleted = App::whereNull('deleted_at')->pluck('display_name', 'aid')->toArray();

		$this->info("Start syncing apps");

		foreach ($apps as $app) {
			$apiProducts = [];

			if (count($app['credentials']) === 0) {
				$this->warn("No creds: {$app['name']}");
				continue;
			};

			foreach ($app['credentials'][0]['apiProducts'] as $product) {
				if (!in_array($product['apiproduct'], $products)) {
					$this->warn("No product: {$app['name']}");
					continue 2;
				}
				$apiProducts[$product['apiproduct']] = ['status' => $product['status']];
			}

			if (count($app['credentials']) > 1) {
				foreach (end($app['credentials'])['apiProducts'] as $product) {
					if (!in_array($product['apiproduct'], $products)) {
						$this->warn("No sandbox product: {$app['name']}");
						continue 2;
					}
					$apiProducts[$product['apiproduct']] = ['status' => $product['status']];
				}
			}

			$this->info("Syncing {$app['name']}");

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

		$this->info('Total apps to be deleted: ' . count($appsToBeDeleted));

		if (count($appsToBeDeleted) > 0) {
			App::whereIn('aid', array_keys($appsToBeDeleted))->delete();
		}

		return 0;
	}
}
