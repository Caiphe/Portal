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

];
