<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api\Admin')->prefix('admin')->group(function () {
    Route::post('/products/{product:slug}/openapi', 'ProductController@openapiUpload')->name('api.product.openapi.upload');
    Route::post('/products/{product:slug}/image', 'ProductController@imageUpload')->name('api.product.image.upload');

    Route::post('sync', 'SyncController@sync')->name('api.sync');
});