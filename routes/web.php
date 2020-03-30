<?php

use App\Services\OpenApiService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.sidebar');
});

Route::get('apps', 'AppController@index')->name('app.index');
Route::get('apps/create', 'AppController@create')->name('app.create');
Route::post('apps', 'AppController@store')->name('app.store');
Route::get('apps/{name}/edit', 'AppController@edit')->name('app.edit');
Route::put('apps/{name}', 'AppController@update')->name('app.update');
Route::delete('apps/{name}', 'AppController@destroy')->name('app.destroy');

Route::get('/products', 'ProductController@index');
Route::get('products/{product:slug}', 'ProductController@show')->name('product.show');
Route::get('products/{product:slug}/download/postman', 'ProductController@downloadPostman')->name('product.download.postman');
Route::get('products/{product:slug}/download/swagger', 'ProductController@downloadSwagger')->name('product.download.swagger');

Route::view('/getting-started', 'templates.getting-started.index');

Auth::routes();
