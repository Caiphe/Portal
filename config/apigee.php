<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Apigee Base
    |--------------------------------------------------------------------------
    |
    | The base url that is used to connect to the developer portal to Apigee.
    |
    */

    'base' => env('APIGEE_BASE'),

    /*
    |--------------------------------------------------------------------------
    | Apigee Base Mint
    |--------------------------------------------------------------------------
    |
    | The base url that is used to connect to the developer portal to Apigee
    | specifically for the monitisation APIs.
    |
    */

    'base_mint' => env('APIGEE_BASE_MINT'),

    /*
    |--------------------------------------------------------------------------
    | Apigee Username
    |--------------------------------------------------------------------------
    |
    | The username that is used to connect to Apigee.
    |
    */

    'username' => env('APIGEE_USERNAME'),

    /*
    |--------------------------------------------------------------------------
    | Apigee Password
    |--------------------------------------------------------------------------
    |
    | The password that is used to connect to Apigee.
    |
    */

    'password' => env('APIGEE_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Apigee allow prefix
    |--------------------------------------------------------------------------
    |
    | The prefixes that are allowed in the Apigee name
    |
    */

    'apigee_allow_prefix' => env('APIGEE_ALLOW_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Apigee deny prefix
    |--------------------------------------------------------------------------
    |
    | The prefixes that are not allowed in the Apigee name
    |
    */

    'apigee_deny_prefix' => env('APIGEE_DENY_PREFIX', ''),

];
