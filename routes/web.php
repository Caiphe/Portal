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

	Route::post('apps/{product}/approve', 'DashboardController@update')->name('app.product.approve');
	Route::post('apps/{product}/revoke', 'DashboardController@update')->name('app.product.revoke');

	Route::get('profile', 'UserController@show')->name('user.profile');
	Route::put('profile/{user}/update', 'UserController@update')->name('user.profile.update');
	Route::post('profile/update/picture', 'UserController@updateProfilePicture')->name('user.profile.update.picture');

	Route::post('profile/2fa/enable', 'UserController@enable2fa')->name('user.2fa.enable');
	Route::post('profile/2fa/disable', 'UserController@disable2fa')->name('user.2fa.disable');
});

Route::namespace('Admin')->prefix('admin')->middleware('can:view-admin')->group(function () {
	Route::get('/', 'HomeController')->name('home');

	// Products
	Route::get('/products', 'ProductController@index')->name('admin.product.index');
	Route::get('/products/{product:slug}/edit', 'ProductController@edit')->name('admin.product.edit');
	Route::put('/products/{product:slug}/update', 'ProductController@update')->name('admin.product.update');

	// Bundles
	Route::get('/bundles', 'BundleController@index')->name('admin.bundle.index');
	Route::get('/bundles/{bundle:slug}/edit', 'BundleController@edit')->name('admin.bundle.edit');
	Route::put('/bundles/{bundle:slug}/update', 'BundleController@update')->name('admin.bundle.update');

	// Page
	Route::get('/pages', 'ContentController@indexPage')->name('admin.page.index');
	Route::get('/pages/{content:slug}/edit', 'ContentController@editPage')->name('admin.page.edit');
	Route::put('/pages/{content:slug}/update', 'ContentController@updatePage')->name('admin.page.update');

	// Documentation
	Route::get('/docs', 'ContentController@indexDoc')->name('admin.doc.index');
	Route::get('/docs/{content:slug}/edit', 'ContentController@editDoc')->name('admin.doc.edit');
	Route::put('/docs/{content:slug}/update', 'ContentController@updateDoc')->name('admin.doc.update');

	// FAQ
	Route::get('/faqs', 'FaqController@index')->name('admin.faq.index');
	Route::get('/faqs/{faq:slug}/edit', 'FaqController@edit')->name('admin.faq.edit');
	Route::put('/faqs/{faq:slug}/update', 'FaqController@update')->name('admin.faq.update');
	Route::get('/faqs/create', 'FaqController@create')->name('admin.faq.create');
	Route::post('/faqs', 'FaqController@store')->name('admin.faq.store');

	// Categories
	// Route::get('/categories', 'FaqController@index')->name('admin.faq.index');
	// Route::get('/categories/{faq:slug}/edit', 'FaqController@edit')->name('admin.faq.edit');
	// Route::put('/categories/{faq:slug}/store', 'FaqController@store')->name('admin.faq.store');
});

Route::post('profile/2fa/verify', 'UserController@verify2fa')->middleware('2fa')->name('user.2fa.verify');

Route::get('dashboard', 'DashboardController@index')->middleware('can:view-dashboard')->name('dashboard');

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
Route::get('faq', 'FaqController@index')->name('faq.show');

Route::get('contact', 'ContactController@index')->name('contact.index');
Route::post('contact', 'ContactController@send')->name('contact.send');

Auth::routes(['verify' => true]);

Route::get('{content:slug}', 'ContentController@show')->name('page.show');
