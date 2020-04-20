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

Route::get('search', 'SearchController')->name('search');

Route::get('apps', 'AppController@index')->middleware('verified')->name('app.index');
Route::get('apps/create', 'AppController@create')->middleware('verified')->name('app.create');
Route::get('apps/{name}/edit', 'AppController@edit')->middleware('verified')->name('app.edit');
Route::post('apps', 'AppController@store')->name('app.store');

Route::put('apps/{name}', 'AppController@update')->middleware('verified')->name('app.update');
Route::delete('apps/{name}', 'AppController@destroy')->middleware('verified')->name('app.destroy');

Route::post('apps/{product}/approve', 'DashboardController@update')->middleware('verified')->name('app.product.approve');
Route::post('apps/{product}/revoke', 'DashboardController@update')->middleware('verified')->name('app.product.revoke');
Route::delete('apps/{id}/complete', 'DashboardController@destroy')->middleware('verified')->name('app.products.complete');

Route::get('products', 'ProductController@index');
Route::get('products/{product:slug}', 'ProductController@show')->name('product.show');
Route::get('products/{product:slug}/download/postman', 'ProductController@downloadPostman')->name('product.download.postman');
Route::get('products/{product:slug}/download/swagger', 'ProductController@downloadSwagger')->name('product.download.swagger');

Route::get('getting-started', 'GettingStartedController@index');
Route::get('getting-started/{content:slug}', 'GettingStartedController@show');

Route::get('faq', 'FaqController@index')->name('faq.index');

Route::get('contact', 'ContactController@index')->name('contact.index');
Route::post('contact', 'ContactController@send')->name('contact.send');

Route::get('profile', 'UserController@show')->middleware('verified')->name('user.profile');
Route::put('profile/{user}/update', 'UserController@update')->middleware('verified')->name('user.profile.update');
Route::post('profile/update/picture', 'UserController@updateProfilePicture')->middleware('verified')->name('user.profile.update.picture');

Route::get('dashboard', 'DashboardController@index')->middleware('verified')->name('dashboard');

Auth::routes(['verify' => true]);

Route::get('{content:slug}', 'ContentController@show')->name('page.show');
