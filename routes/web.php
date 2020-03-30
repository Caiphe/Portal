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

// Route::get('/', function () {
//     return view('layouts.master');
// })->name('home');
Route::get('/', 'HomeController@home')->name('home');

Route::get('/products', 'ProductController@index');
Route::get('products/{product:slug}', 'ProductController@show')->name('product.show');
Route::get('products/{product:slug}/download/postman', 'ProductController@downloadPostman')->name('product.download.postman');
Route::get('products/{product:slug}/download/swagger', 'ProductController@downloadSwagger')->name('product.download.swagger');

Route::get('/getting-started', 'GettingStartedController@index');
Route::get('getting-started/{content:slug}', 'GettingStartedController@show');

Route::get('profile', 'UserController@show')->name('user.profile');
Route::put('profile/{user}/update', 'UserController@update')->name('user.profile.update');
Route::post('profile/update/picture', 'UserController@updateProfilePicture')->name('user.profile.update.picture');

Auth::routes();
