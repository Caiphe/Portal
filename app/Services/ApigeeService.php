<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * 
 */
class ApigeeService
{
    public static function askFor($url)
    {
        return Http::withBasicAuth(env('APIGEE_USERNAME'), env('APIGEE_PASSWORD'))->get(env('APIGEE_BASE') . $url);
    }

    public function send($body, $user, $method = "POST")
    {
        
    }
}