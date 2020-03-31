<?php

// Auth::loginUsingId(1);

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

Route::get('/', 'HomeController@home')->name('home');

Route::get('apps', 'AppController@index')->name('app.index');
Route::get('apps/create', 'AppController@create')->name('app.create');
Route::get('apps/{name}/edit', 'AppController@edit')->name('app.edit');
Route::post('apps', 'AppController@store')->name('app.store');
Route::put('apps/{name}', 'AppController@update')->name('app.update');
Route::delete('apps/{name}', 'AppController@destroy')->name('app.destroy');
Route::post('apps/{product}/approve', 'DashboardController@approve')->name('app.product.approve');
Route::post('apps/{product}/revoke', 'DashboardController@revoke')->name('app.product.revoke');

Route::get('products', 'ProductController@index');
Route::get('products/{product:slug}', 'ProductController@show')->name('product.show');
Route::get('products/{product:slug}/download/postman', 'ProductController@downloadPostman')->name('product.download.postman');
Route::get('products/{product:slug}/download/swagger', 'ProductController@downloadSwagger')->name('product.download.swagger');

Route::get('getting-started', 'GettingStartedController@index');
Route::get('getting-started/{content:slug}', 'GettingStartedController@show');

Route::get('profile', 'UserController@show')->name('user.profile');
Route::put('profile/{user}/update', 'UserController@update')->name('user.profile.update');
Route::post('profile/update/picture', 'UserController@updateProfilePicture')->name('user.profile.update.picture');

Route::get('dashboard', 'DashboardController@index')->name('dashboard');

Auth::routes();
