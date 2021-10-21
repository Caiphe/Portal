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

Route::get('/debug-sentry', function () {
	throw new Exception('Sentry error!');
});

Route::get('/', 'HomeController')->name('home');

Route::get('search', 'SearchController')->name('search');

Route::middleware(['auth', 'verified', '2fa'])->group(function () {

	Route::get('apps', 'AppController@index')->name('app.index');
	Route::get('apps/create', 'AppController@create')->name('app.create');
	Route::get('apps/{app:slug}/edit', 'AppController@edit')->name('app.edit');
	Route::post('apps', 'AppController@store')->name('app.store');

	Route::put('apps/{app:slug}', 'AppController@update')->name('app.update');
	Route::delete('apps/{app:slug}', 'AppController@destroy')->name('app.destroy');

	Route::get('apps/{app:aid}/credentials/{type}', 'AppController@getCredentials')->middleware('can:access-own-app,app')->name('app.credentials');
	Route::post('apps/{app:aid}/go-live', 'AppController@goLive')->middleware('can:access-own-app,app')->name('app.go-live');
	Route::get('apps/{app:aid}/kyc/{group}', 'AppController@kyc')->middleware('can:access-own-app,app')->name('app.kyc');
	Route::post('apps/{app:aid}/kyc/{group}', 'AppController@kycStore')->middleware('can:access-own-app,app')->name('app.kyc.store');
	Route::post('apps/{app:aid}/credentials/request/renew/{type}', 'AppController@requestRenewCredentials')->middleware('can:access-own-app,app')->name('app.credentials.request-renew');
	Route::get('apps/{app:aid}/credentials/renew/{type}', 'AppController@renewCredentials')->middleware(['can:access-own-app,app', 'signed'])->name('app.credentials.renew');

	Route::get('profile', 'UserController@show')->name('user.profile');
	Route::put('profile/update', 'UserController@update')->name('user.profile.update');
	Route::post('profile/update/picture', 'UserController@updateProfilePicture')->name('user.profile.update.picture');

	Route::post('profile/2fa/enable', 'UserController@enable2fa')->name('user.2fa.enable');
	Route::post('profile/2fa/disable', 'UserController@disable2fa')->name('user.2fa.disable');

    Route::get('teams', 'CompanyTeamsController@index')->name('teams.listing');
    Route::get('teams/{id}/team', 'CompanyTeamsController@show')->name('team.show');
    Route::get('teams/create', 'CompanyTeamsController@create')->name('teams.create');
    Route::get('teams/{id}/edit', 'CompanyTeamsController@edit')->middleware('can:can:administer-team')->name('teams.edit');
    Route::post('teams/{id}/update', 'CompanyTeamsController@update')->name('teams.update');
    Route::post('teams/store', 'CompanyTeamsController@store')->name('teams.store');
    Route::post('teams/leave', 'CompanyTeamsController@leave')->name('teams.leave.team');
    Route::post('teams/invite', 'CompanyTeamsController@invite')->middleware('can:can:administer-team')->name('teams.invite');
    Route::any('teams/accept', 'CompanyTeamsController@accept')->name('teams.invite.accept');
    Route::any('teams/reject', 'CompanyTeamsController@reject')->name('teams.invite.deny');
    Route::any('teams/ownership', 'CompanyTeamsController@ownership')->middleware('can:can:administer-team')->name('teams.ownership.invite');
});

Route::namespace('Admin')->prefix('admin')->middleware(['auth', 'verified', '2fa', 'can:view-admin'])->group(function () {
	Route::get('/', 'HomeController')->name('admin.home');

	// Products
	Route::get('products', 'ProductController@index')->middleware('can:administer-products')->name('admin.product.index');
	Route::get('products/{product:slug}/edit', 'ProductController@edit')->middleware('can:administer-products')->name('admin.product.edit');
	Route::put('products/{product:slug}/update', 'ProductController@update')->middleware('can:administer-products')->name('admin.product.update');

	// Bundles
	Route::get('bundles', 'BundleController@index')->middleware('can:administer-products')->name('admin.bundle.index');
	Route::get('bundles/{bundle:slug}/edit', 'BundleController@edit')->middleware('can:administer-products')->name('admin.bundle.edit');
	Route::put('bundles/{bundle:slug}/update', 'BundleController@update')->middleware('can:administer-products')->name('admin.bundle.update');

	// Page
	Route::get('pages', 'ContentController@indexPage')->middleware('can:administer-content')->name('admin.page.index');
	Route::get('pages/{content:slug}/edit', 'ContentController@editPage')->middleware('can:administer-content')->name('admin.page.edit');
	Route::put('pages/{content:slug}/update', 'ContentController@updatePage')->middleware('can:administer-content')->name('admin.page.update');
	Route::get('pages/create', 'ContentController@createPage')->middleware('can:administer-content')->name('admin.page.create');
	Route::post('pages', 'ContentController@storePage')->middleware('can:administer-content')->name('admin.page.store');
	Route::delete('pages{content:slug}/delete', 'ContentController@destroyPage')->middleware('can:administer-content')->name('admin.page.delete');

	// Documentation
	Route::get('docs', 'ContentController@indexDoc')->middleware('can:administer-content')->name('admin.doc.index');
	Route::get('docs/{content:slug}/edit', 'ContentController@editDoc')->middleware('can:administer-content')->name('admin.doc.edit');
	Route::put('docs/{content:slug}/update', 'ContentController@updateDoc')->middleware('can:administer-content')->name('admin.doc.update');
	Route::get('docs/create', 'ContentController@createDoc')->middleware('can:administer-content')->name('admin.doc.create');
	Route::post('docs', 'ContentController@storeDoc')->middleware('can:administer-content')->name('admin.doc.store');
	Route::delete('docs{content:slug}/delete', 'ContentController@destroyDoc')->middleware('can:administer-content')->name('admin.doc.delete');

	// FAQ
	Route::get('faqs', 'FaqController@index')->middleware('can:administer-content')->name('admin.faq.index');
	Route::get('faqs/{faq:slug}/edit', 'FaqController@edit')->middleware('can:administer-content')->name('admin.faq.edit');
	Route::put('faqs/{faq:slug}/update', 'FaqController@update')->middleware('can:administer-content')->name('admin.faq.update');
	Route::get('faqs/create', 'FaqController@create')->middleware('can:administer-content')->name('admin.faq.create');
	Route::post('faqs', 'FaqController@store')->middleware('can:administer-content')->name('admin.faq.store');
	Route::delete('faqs{faq:slug}/delete', 'FaqController@destroy')->middleware('can:administer-content')->name('admin.faq.delete');

	// Categories
	Route::get('categories', 'CategoryController@index')->middleware('can:administer-content')->name('admin.category.index');
	Route::get('categories/{category:slug}/edit', 'CategoryController@edit')->middleware('can:administer-content')->name('admin.category.edit');
	Route::put('categories/{category:slug}/update', 'CategoryController@update')->middleware('can:administer-content')->name('admin.category.update');
	Route::get('categories/create', 'CategoryController@create')->middleware('can:administer-content')->name('admin.category.create');
	Route::post('categories', 'CategoryController@store')->middleware('can:administer-content')->name('admin.category.store');
	Route::delete('categories{category:slug}/delete', 'CategoryController@destroy')->middleware('can:administer-content')->name('admin.category.delete');

	// Dashboard
	Route::get('dashboard', 'DashboardController@index')->middleware('can:administer-dashboard')->name('admin.dashboard.index');
	Route::post('apps/{app:aid}/go-live', 'AppController@approve')->middleware('can:administer-dashboard')->name('app.approve');
	Route::post('apps/{app:aid}/kyc-status', 'DashboardController@updateKycStatus')->middleware('can:administer-dashboard')->name('app.kyc-status.update');
	Route::post('apps/{product}/approve', 'DashboardController@update')->middleware('can:administer-dashboard')->name('app.product.approve');
	Route::post('apps/{product}/revoke', 'DashboardController@update')->middleware('can:administer-dashboard')->name('app.product.revoke');
	Route::post('dashboard/{app:aid}/credentials/renew/{type}', 'DashboardController@renewCredentials')->middleware('can:administer-dashboard')->name('admin.credentials.renew');
	Route::post('apps/{app:aid}/status', 'DashboardController@updateAppStatus')->middleware('can:administer-dashboard')->name('admin.app.status-update');
    Route::get('apps/create/{user?}', 'DashboardController@createUserApp')->middleware('can:administer-dashboard')->name('admin.app.create');
    // Global search
	Route::get('search', 'SearchController')->name('admin.search');

	// User management
	Route::get('users', 'UserController@index')->middleware('can:administer-users')->name('admin.user.index');
	Route::get('users/{user}/edit', 'UserController@edit')->middleware('can:administer-users')->name('admin.user.edit');
	Route::put('users/{user}/update', 'UserController@update')->middleware('can:administer-users')->name('admin.user.update');
	Route::get('users/create', 'UserController@create')->middleware('can:administer-users')->name('admin.user.create');
	Route::post('users/store', 'UserController@store')->middleware('can:administer-users')->name('admin.user.store');
});

Route::namespace('Api\Admin')->prefix('api/admin')->group(function () {
	Route::post('/products/{product:slug}/openapi', 'ProductController@openapiUpload')->middleware('can:administer-content')->name('api.product.openapi.upload');
	Route::post('/products/{product:slug}/image', 'ProductController@imageUpload')->middleware('can:administer-content')->name('api.product.image.upload');

	Route::post('sync', 'SyncController@sync')->middleware('can:administer-dashboard')->name('api.sync');
	Route::post('sync/products', 'SyncController@syncProducts')->middleware('can:administer-dashboard')->name('api.sync.products');
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
Route::post('contact', 'ContactController@send')->middleware('throttle:1,5')->name('contact.send');

Auth::routes(['verify' => true]);

Route::get('{content:slug}', 'ContentController@show')->name('page.show');
