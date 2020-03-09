<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * 
 */
class ApigeeService
{
    public static function get(string $url)
    {
        return self::HttpWithBasicAuth()->get(env('APIGEE_BASE') . $url);
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

    public static function updateApp(array $data)
    {
        $user = auth()->user();
        return self::put("developers/{$user->email}/apps/{$data['appName']}", $data);
    }

    protected static function HttpWithBasicAuth()
    {
        return Http::withBasicAuth(env('APIGEE_USERNAME'), env('APIGEE_PASSWORD'));
    }
}