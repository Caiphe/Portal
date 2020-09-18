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

Route::middleware(['verified', '2fa'])->group(function () {
	Route::get('apps', 'AppController@index')->name('app.index');
	Route::get('apps/create', 'AppController@create')->name('app.create');
	Route::get('apps/{app:slug}/edit', 'AppController@edit')->name('app.edit');
	Route::post('apps', 'AppController@store')->name('app.store');

	Route::put('apps/{app:slug}', 'AppController@update')->name('app.update');
	Route::delete('apps/{app:slug}', 'AppController@destroy')->name('app.destroy');

	Route::get('apps/{app:aid}/credentials/{type}', 'AppController@getCredentials')->name('app.credentials');

	Route::get('profile', 'UserController@show')->name('user.profile');
	Route::put('profile/{user}/update', 'UserController@update')->name('user.profile.update');
	Route::post('profile/update/picture', 'UserController@updateProfilePicture')->name('user.profile.update.picture');

	Route::post('profile/2fa/enable', 'UserController@enable2fa')->name('user.2fa.enable');
	Route::post('profile/2fa/disable', 'UserController@disable2fa')->name('user.2fa.disable');
});

Route::namespace('Admin')->prefix('admin')->middleware(['can:view-admin', '2fa'])->group(function () {
	Route::redirect('home', '/admin/products')->name('admin.home');

	// Products
	Route::get('products', 'ProductController@index')->name('admin.product.index');
	Route::get('products/{product:slug}/edit', 'ProductController@edit')->name('admin.product.edit');
	Route::put('products/{product:slug}/update', 'ProductController@update')->name('admin.product.update');

	// Bundles
	Route::get('bundles', 'BundleController@index')->name('admin.bundle.index');
	Route::get('bundles/{bundle:slug}/edit', 'BundleController@edit')->name('admin.bundle.edit');
	Route::put('bundles/{bundle:slug}/update', 'BundleController@update')->name('admin.bundle.update');

	// Page
	Route::get('pages', 'ContentController@indexPage')->name('admin.page.index');
	Route::get('pages/{content:slug}/edit', 'ContentController@editPage')->name('admin.page.edit');
	Route::put('pages/{content:slug}/update', 'ContentController@updatePage')->name('admin.page.update');
	Route::get('pages/create', 'ContentController@createPage')->name('admin.page.create');
	Route::post('pages', 'ContentController@storePage')->name('admin.page.store');
	Route::delete('pages{content:slug}/delete', 'ContentController@destroyPage')->name('admin.page.delete');

	// Documentation
	Route::get('docs', 'ContentController@indexDoc')->name('admin.doc.index');
	Route::get('docs/{content:slug}/edit', 'ContentController@editDoc')->name('admin.doc.edit');
	Route::put('docs/{content:slug}/update', 'ContentController@updateDoc')->name('admin.doc.update');
	Route::get('docs/create', 'ContentController@createDoc')->name('admin.doc.create');
	Route::post('docs', 'ContentController@storeDoc')->name('admin.doc.store');
	Route::delete('docs{content:slug}/delete', 'ContentController@destroyDoc')->name('admin.doc.delete');

	// FAQ
	Route::get('faqs', 'FaqController@index')->name('admin.faq.index');
	Route::get('faqs/{faq:slug}/edit', 'FaqController@edit')->name('admin.faq.edit');
	Route::put('faqs/{faq:slug}/update', 'FaqController@update')->name('admin.faq.update');
	Route::get('faqs/create', 'FaqController@create')->name('admin.faq.create');
	Route::post('faqs', 'FaqController@store')->name('admin.faq.store');
	Route::delete('faqs{faq:slug}/delete', 'FaqController@destroy')->name('admin.faq.delete');

	// Categories
	Route::get('categories', 'CategoryController@index')->name('admin.category.index');
	Route::get('categories/{category:slug}/edit', 'CategoryController@edit')->name('admin.category.edit');
	Route::put('categories/{category:slug}/update', 'CategoryController@update')->name('admin.category.update');
	Route::get('categories/create', 'CategoryController@create')->name('admin.category.create');
	Route::post('categories', 'CategoryController@store')->name('admin.category.store');
	Route::delete('categories{category:slug}/delete', 'CategoryController@destroy')->name('admin.category.delete');

	// Dashboard
	Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard.index');
	Route::post('apps/{product}/approve', 'DashboardController@update')->name('app.product.approve');
	Route::post('apps/{product}/revoke', 'DashboardController@update')->name('app.product.revoke');

	// Global search
	Route::get('search', 'SearchController')->name('admin.search');

	// User management
	Route::get('users', 'UserController@index')->name('admin.user.index');
	Route::get('users/{user}/edit', 'UserController@edit')->name('admin.user.edit');
	Route::put('users/{user}/update', 'UserController@update')->name('admin.user.update');
	Route::get('users/create', 'UserController@create')->name('admin.user.create');
	Route::post('users/store', 'UserController@store')->name('admin.user.store');
});

Route::post('profile/2fa/verify', 'UserController@verify2fa')->middleware('2fa')->name('user.2fa.verify');

Route::get('products', 'ProductController@index')->name('product.index');
Route::get('products/{product:slug}', 'ProductController@show')->name('product.show');
Route::get('products/{product:slug}/download/postman', 'ProductController@downloadPostman')->name('product.download.postman');
Route::get('products/{product:slug}/download/swagger', 'ProductController@downloadSwagger')->name('product.download.swagger');

Route::get('categories/{category:slug}', 'CategoryController@show')->name('category.show');

Route::get('bundles', 'BundleController@index')->name('bundle.index');
Route::get('bundles/{bundle:slug}', 'BundleController@show')->name('bundle.show');

Route::get('getting-started', 'GettingStartedController@index')->name('doc.index');
Route::get('getting-started/{content:slug}', 'GettingStartedController@show')->name('doc.show');

Route::get('faq', 'FaqController@index')->name('faq.index');
Route::get('faq/{faq:slug}', 'FaqController@show')->name('faq.show');

Route::get('contact', 'ContactController@index')->name('contact.index');
Route::post('contact', 'ContactController@send')->middleware('throttle:1,1')->name('contact.send');

Auth::routes(['verify' => true]);

Route::get('{content:slug}', 'ContentController@show')->name('page.show');
