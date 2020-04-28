<?php

namespace App\Console\Commands;

use App\App;
use App\Services\ApigeeService;
use Illuminate\Console\Command;

class SyncApps extends Command {
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
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$this->info("Getting apps from Apigee");
		$apps = ApigeeService::getOrgApps('approved', 0);

		$this->info("Start syncing apps");

		foreach ($apps['app'] as $app) {
			$this->info("Syncing {$app['name']}");

			$attributes = ApigeeService::getAppAttributes($app['attributes']);

			$a = App::updateOrCreate(
				["aid" => $app['appId']], [
					"aid" => $app['appId'],
					"name" => $app['name'],
					"display_name" => $attributes['DisplayName'] ?? $app['name'],
					"callback_url" => $app['callbackUrl'],
					"attributes" => json_encode($attributes),
					"developer_id" => $app['developerId'],
					"status" => $app['status'],
					"description" => $attributes['Description'] ?? '',
					"updated_at" => date('Y-m-d H:i:s', $app['lastModifiedAt'] / 1000),
					"created_at" => date('Y-m-d H:i:s', $app['createdAt'] / 1000),
				]);

			$a->products()->sync(array_reduce($app['credentials']['apiProducts'], function ($carry, $product) {
				$carry[$product['apiproduct']] = ['status' => $product['status']];
				return $carry;
			}, []));
		}
	}
}
