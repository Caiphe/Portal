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
class ApigeeService
{
    public static function get(string $url)
    {
        return self::HttpWithBasicAuth()->get(env('APIGEE_BASE') . $url)->json();
    }

    public static function post(string $url, array $data)
    {
        return self::HttpWithBasicAuth()->post(env('APIGEE_BASE') . $url, $data);
    }

    public static function put(string $url, array $data)
    {
        return self::HttpWithBasicAuth()->put(env('APIGEE_BASE') . $url, $data);
    }

    public static function delete(string $url)
    {
        return self::HttpWithBasicAuth()->delete(env('APIGEE_BASE') . $url);
    }

    public static function createApp(array $data)
    {
        $user = auth()->user();
        return self::post("developers/{$user->email}/apps", $data);
    }

    public static function updateApp(string $name, array $data)
    {
        $user = auth()->user();
        return self::put("developers/{$user->email}/apps/{$name}", $data);
    }

    public static function getAppAttributes(array $attributes)
    {
        $a = [];
        foreach ($attributes as $attribute) {
            $a[$attribute['name']] = $attribute['value'];
        }
        return $a;
    }

    public static function getOrgApps(string $status)
    {
        return self::get("/apps?expand=true&status={$status}");
    }

    public static function getDevelopers()
    {
        return self::get('/developers?expand=true');
    }

    public static function getAppCountries(array $products)
    {
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

    public static function updateProductStatus(string $id, string $app, string $key, string $product, string $action)
    {
        return self::post("developers/{$id}/apps/{$app}/{$key}/apiproducts/{$product}", ['action' => $action]);
    }

    protected static function HttpWithBasicAuth()
    {
        return Http::withBasicAuth(env('APIGEE_USERNAME'), env('APIGEE_PASSWORD'));
    }
}
