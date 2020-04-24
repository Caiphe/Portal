<?php

namespace App\Services;

use App\Country;
use App\Product;
use Illuminate\Support\Facades\Http;

/**
 * This is a helper service to connect to Apigee.
 * It has the base connections through GET, POST, PUT and Delete but has
 * some helper functions on top of it for convenience.
 */
class ApigeeService {
	public static function get(string $url) {
		return self::HttpWithBasicAuth()->get(config('apigee.base') . $url)->json();
	}

	public static function post(string $url, array $data) {
		return self::HttpWithBasicAuth()->post(config('apigee.base') . $url, $data);
	}

	public static function put(string $url, array $data) {
		return self::HttpWithBasicAuth()->put(config('apigee.base') . $url, $data);
	}

	public static function delete(string $url) {
		return self::HttpWithBasicAuth()->delete(config('apigee.base') . $url);
	}

	public static function createApp(array $data) {
		$user = auth()->user();
		return self::post("developers/{$user->email}/apps", $data);
	}

	public static function updateApp(array $data) {
		$user = auth()->user();
		$name = $data['name'];
		$key = $data['key'];
		$apiProducts = $data['apiProducts'];
		$originalProducts = $data['originalProducts'];
		$removedProducts = array_diff($originalProducts, $apiProducts);

		$updatedProducts = self::post("developers/{$user->email}/apps/{$name}/keys/{$key}", ["apiProducts" => $apiProducts]);
		$updatedDetails = self::put("developers/{$user->email}/apps/{$name}", [
			"name" => $name,
			"attributes" => $data['attributes'],
			"callbackUrl" => $data['callbackUrl'],
		]);

		foreach ($removedProducts as $product) {
			self::delete("developers/{$user->email}/apps/{$name}/keys/{$key}/apiproducts/{$product}");
		}

		return $updatedDetails;
	}

	public static function getAppAttributes(array $attributes) {
		$a = [];
		foreach ($attributes as $attribute) {
			$a[$attribute['name']] = $attribute['value'];
		}
		return $a;
	}

	public static function getOrgApps(string $status = 'approved', int $rows = 10) {
		$status = $status === "all" ? "" : "&status={$status}";
		$rows = $rows === 0 ? "" : "&rows={$rows}";
		$apps = self::get("/apps?expand=true{$rows}{$status}");

		for ($i = 0; $i < count($apps['app']); $i++) {
			if (!isset($apps['app'][$i]['credentials'])) {
				continue;
			}

			$apps['app'][$i]['credentials'] = self::getLatestCredentials($apps['app'][$i]['credentials']);
		}

		return $apps;
	}

	public static function getDeveloperDetails(string $developer_id) {
		return self::get("/developers/{$developer_id}?expand=true");
	}

	public static function getDeveloperApps($email) {
		$apps = self::get("developers/{$email}/apps/?expand=true");

		for ($i = 0; $i < count($apps['app']); $i++) {
			if (!isset($apps['app'][$i]['credentials'])) {
				continue;
			}

			$apps['app'][$i]['credentials'] = self::getLatestCredentials($apps['app'][$i]['credentials']);
		}

		return $apps;
	}

	public static function getDeveloperApp($email, $appName) {
		$apps = self::get("developers/{$email}/apps/{$appName}/?expand=true");

		$apps['credentials'] = self::getLatestCredentials($apps['credentials']);

		return $apps;
	}

	public static function getAppCountries(array $products) {
		$countries = [];
		foreach ($products as $product) {
			$countries[] = array_unique(explode(',', (Product::where('name', $product)->select('locations')->get()->implode('locations', ','))));
		}

		$filteredCountries = Country::whereIn('code', $countries)->get();

		$countries = $filteredCountries->each(function ($query) {
			return $query;
		})->pluck('name', 'code');

		return $countries;
	}

	public static function updateProductStatus(string $id, string $app, string $key, string $product, string $action) {
		return self::post("developers/{$id}/apps/{$app}/{$key}/apiproducts/{$product}", ['action' => $action]);
	}

	protected static function HttpWithBasicAuth() {
		return Http::withBasicAuth(config('apigee.username'), config('apigee.password'));
	}

	protected static function getLatestCredentials(array $credentials) {
		for ($i = count($credentials) - 1; $i >= 0; $i--) {
			if ($credentials[$i]['status'] === 'approved') {
				return $credentials[$i];
			}
		}

		return end($credentials);
	}
}
