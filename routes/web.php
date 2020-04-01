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

Route::get('/', 'HomeController')->name('home');

Route::get('apps', 'AppController@index')->middleware('auth')->name('app.index');
Route::get('apps/create', 'AppController@create')->middleware('auth')->name('app.create');
Route::get('apps/{name}/edit', 'AppController@edit')->middleware('auth')->name('app.edit');
Route::post('apps', 'AppController@store')->name('app.store');
Route::put('apps/{name}', 'AppController@update')->middleware('auth')->name('app.update');
Route::delete('apps/{name}', 'AppController@destroy')->middleware('auth')->name('app.destroy');
Route::post('apps/{product}/approve', 'DashboardController@approve')->middleware('auth')->name('app.product.approve');
Route::post('apps/{product}/revoke', 'DashboardController@revoke')->middleware('auth')->name('app.product.revoke');
Route::post('apps/{name}/products/approve', 'DashboardController@approveAll')->middleware('auth')->name('app.products.approve');
Route::post('apps/{name}/products/revoke', 'DashboardController@revokeAll')->middleware('auth')->name('app.products.revoke');
Route::post('apps/{name}/products/complete', 'DashboardController@complete')->middleware('auth')->name('app.products.complete');

Route::get('products', 'ProductController@index');
Route::get('products/{product:slug}', 'ProductController@show')->name('product.show');
Route::get('products/{product:slug}/download/postman', 'ProductController@downloadPostman')->name('product.download.postman');
Route::get('products/{product:slug}/download/swagger', 'ProductController@downloadSwagger')->name('product.download.swagger');

Route::get('getting-started', 'GettingStartedController@index');
Route::get('getting-started/{content:slug}', 'GettingStartedController@show');

Route::get('faq', 'FaqController@index')->name('faq.index');

Route::get('contact', 'ContactController@index')->name('contact.index');
Route::post('contact/sendMail', 'ContactController@sendMail')->name('contact.sendEmail');

Route::get('profile', 'UserController@show')->middleware('auth')->name('user.profile');
Route::put('profile/{user}/update', 'UserController@update')->middleware('auth')->name('user.profile.update');
Route::post('profile/update/picture', 'UserController@updateProfilePicture')->middleware('auth')->name('user.profile.update.picture');

Route::get('dashboard', 'DashboardController@index')->middleware('auth')->name('dashboard');

Auth::routes();
