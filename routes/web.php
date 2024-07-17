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

Route::middleware(['auth', 'verified'])->group(function () {
	Route::post('user/2fa/reset-request', 'UserController@reset2farequest')->name('2fa.reset.request');
    Route::get('profile', 'UserController@show')->name('user.profile');
});

Route::middleware(['auth', 'verified', '2fa'])->group(function () {

	Route::get('apps', 'AppController@index')->name('app.index');
	Route::get('apps/create', 'AppController@create')->name('app.create');
	Route::get('apps/{app:slug}/edit', 'AppController@edit')->name('app.edit');
	Route::post('apps', 'AppController@store')->name('app.store');
	Route::post('app/name-check', 'AppController@checkAppName')->name('app.name.check');

	Route::put('apps/{app:slug}', 'AppController@update')->name('app.update');
	Route::put('apps/{app:aid}/custom-attributes', 'AppController@updateCustomAttributes')->name('app.update.attributes');
	Route::put('admin/apps/{app:aid}/custom-attributes/save', 'AppController@saveCustomAttributeFromApigee')->name('app.save.attributes');
	Route::delete('apps/{app:slug}', 'AppController@destroy')->name('app.destroy');

	Route::get('apps/{app:aid}/credentials/{type}', 'AppController@getCredentials')->middleware('can:access-own-app,app')->name('app.credentials');
	Route::post('apps/{app:aid}/go-live', 'AppController@goLive')->middleware('can:access-own-app,app')->name('app.go-live');
	Route::get('apps/{app:aid}/kyc/{group}', 'AppController@kyc')->middleware('can:access-own-app,app')->name('app.kyc');
	Route::post('apps/{app:aid}/kyc/{group}', 'AppController@kycStore')->middleware('can:access-own-app,app')->name('app.kyc.store');
	Route::post('apps/{app:aid}/credentials/request/renew/{type}', 'AppController@requestRenewCredentials')->middleware('can:access-own-app,app')->name('app.credentials.request-renew');
	Route::get('apps/{app:aid}/credentials/renew/{type}', 'AppController@renewCredentials')->middleware(['can:access-own-app,app', 'signed'])->name('app.credentials.renew');


	Route::put('profile/update', 'UserController@update')->middleware('validateReferer')->name('user.profile.update');
	Route::post('profile/update/picture', 'UserController@updateProfilePicture')->name('user.profile.update.picture');

	Route::post('profile/2fa/enable', 'UserController@enable2fa')->name('user.2fa.enable');
	Route::post('profile/2fa/disable', 'UserController@disable2fa')->name('user.2fa.disable');

    Route::get('teams', 'CompanyTeamsController@index')->name('teams.listing');
    Route::get('teams/{id}/team', 'CompanyTeamsController@show')->name('team.show');
    Route::get('teams/create', 'CompanyTeamsController@create')->name('teams.create');
    Route::get('teams/{id}/edit', 'CompanyTeamsController@edit')->middleware('can:administer-team,id')->name('teams.edit');
    Route::put('teams/{id}/update', 'CompanyTeamsController@update')->middleware('can:administer-team,id')->name('teams.update');
    Route::post('teams/store', 'CompanyTeamsController@store')->name('teams.store');
    Route::post('teams/{team}/leave', 'CompanyTeamsController@leave')->middleware('can:administer-own-team,team')->name('teams.leave.team');
    Route::post('teams/{team}/remove', 'CompanyTeamsController@remove')->middleware('can:administer-own-team,team')->name('teams.remove.team');
    Route::post('teams/{id}/invite', 'CompanyTeamsController@invite')->middleware('can:administer-team,id')->name('teams.invite');
    Route::any('teams/accept', 'CompanyTeamsController@accept')->name('teams.invite.accept');
    Route::any('teams/reject', 'CompanyTeamsController@reject')->name('teams.invite.deny');
    Route::any('teams/{team}/ownership', 'CompanyTeamsController@ownership')->middleware('can:administer-team-by-owner,team')->name('teams.ownership.invite');
    Route::post('teams/{id}/user/role', 'CompanyTeamsController@roleUpdate')->middleware('can:administer-team,id')->name('teams.user.role');
    Route::post('teams/{team}/delete', 'CompanyTeamsController@delete')->middleware('can:administer-team-by-owner,team')->name('teams.delete');
    Route::post('teams/{team}/leave/owner', 'CompanyTeamsController@leaveMakeOwner')->middleware('can:administer-team-by-owner,team')->name('teams.leave.make.owner');


	// Notification
	Route::post('notification/{notification:id}/read', 'NotificationController@read')->name('notification.read');
	Route::post('notifications/read-all', 'NotificationController@readAll')->name('notification.read.all');
	Route::post('notifications/clear-all', 'NotificationController@clearAll')->name('notification.clear.all');
	Route::get('notifications/fetch-all', 'NotificationController@fetchNotification')->name('notification.fetch.all');
	Route::get('notifications/count', 'NotificationController@notificationsCount')->name('notifications.count');
	// Opco admin role request
	Route::post('/opco-admin-role-request/store', 'OpcoRoleRequestController@store')->middleware(['can:request-opco-admin-role'])->name('opco-admin-role.store');
});


Route::namespace('Admin')->prefix('admin')->middleware(['auth', 'verified', '2fa', 'can:view-admin'])->group(function () {
	Route::get('/', 'HomeController')->name('admin.home');

	// Tasks
	Route::get('/tasks', 'TaskController@index')->middleware(['auth', 'verified', '2fa', 'can:administer-task-panel'])->name('admin.task.index');
	Route::post('user/{user}/2fa/reset-confirm', 'UserController@resetTwofaConfirm')->name('2fa.reset.confirm');

	// User deletion request & Action
	Route::post('user/{user}/delete-request', 'UserController@requestUserDeletion')->name('user.delete.request');
	Route::post('user/{user}/delete-action', 'UserController@delectionAction')->middleware('can:administer-content')->name('user.delete.action');

	// Opco role status
	Route::post('/opco-role-request/{id}/approve', 'OpcoRoleRequestActionController@approve')->name('admin.opco.approve');
	Route::post('/opco-role-request/{id}/deny', 'OpcoRoleRequestActionController@deny')->name('admin.opco.deny');

	// Tasks Panel
	Route::get('/tasks', 'TaskController@index')->name('admin.task.index');

	// Maintenance banner
	Route::get('/settings/maintenance', 'MaintenanceController@create')->middleware('can:administer-content')->name('admin.setting.maintenance');
	Route::post('/settings/maintenance-create', 'MaintenanceController@store')->middleware('can:administer-content')->name('admin.maintenance.store');

	// Products
	Route::get('products', 'ProductController@index')->middleware('can:administer-products')->name('admin.product.index');
	Route::get('products/{product:slug}/edit', 'ProductController@edit')->middleware('can:administer-products')->name('admin.product.edit');
	Route::put('products/{product:slug}/update', 'ProductController@update')->middleware('can:administer-products')->name('admin.product.update');

	// Bundles
	// Route::get('bundles', 'BundleController@index')->middleware('can:administer-products')->name('admin.bundle.index');
	// Route::get('bundles/{bundle:slug}/edit', 'BundleController@edit')->middleware('can:administer-products')->name('admin.bundle.edit');
	// Route::put('bundles/{bundle:slug}/update', 'BundleController@update')->middleware('can:administer-products')->name('admin.bundle.update');

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
	Route::post('categories', 'CategoryController@store')->middleware('can:administer-content')->name('admin.category.store');

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
	Route::post('users/{user}/change-status', 'UserController@changeStatus')->middleware('can:administer-users')->name('admin.user.status');
	Route::get('users/create', 'UserController@create')->middleware('can:administer-users')->name('admin.user.create');
	Route::post('users/store', 'UserController@store')->middleware('can:administer-users')->name('admin.user.store');
	Route::put('users/{user}/verify', 'UserController@verifyEmail')->middleware('can:administer-content')->name('admin.user.verify');

	// Team - Company management
    Route::prefix('teams')->middleware('can:administer-users')->group(function () {
        Route::get('/', 'TeamController@index')->name('admin.team.index');
        Route::get('/{team:id}/team', 'TeamController@show')->name('admin.team.show');
        Route::delete('/{team}/delete', 'TeamController@destroy')->name('admin.team.delete');
        Route::get('create', 'TeamController@create')->name('admin.team.create');
        Route::post('store', 'TeamController@store')->name('admin.team.store');
        Route::get('/{team:id}/edit', 'TeamController@edit')->name('admin.team.edit');
        Route::post('/update/{team:id}', 'TeamController@update')->name('admin.team.update');
    });

});

Route::namespace('Api\Admin')->prefix('api/admin')->group(function () {
	Route::post('/products/{product:slug}/openapi', 'ProductController@openapiUpload')->middleware('can:administer-content')->name('api.product.openapi.upload');
	Route::post('/products/{product:slug}/image', 'ProductController@imageUpload')->middleware('can:administer-content')->name('api.product.image.upload');
	Route::post('/editor/upload', 'MediaController@upload')->middleware('can:administer-content')->name('api.editor.upload');

	Route::post('sync', 'SyncController@sync')->middleware('can:administer-dashboard')->name('api.sync');
	Route::post('sync/products', 'SyncController@syncProducts')->middleware('can:administer-dashboard')->name('api.sync.products');
	Route::post('sync/apps', 'SyncController@syncApps')->middleware('can:administer-dashboard')->name('api.sync.apps');

	Route::post('sync-all/', 'SyncController@syncData')->middleware('can:administer-dashboard')->name('api.sync.all');
});

Route::post('profile/2fa/verify', 'UserController@verify2fa')->middleware('throttle:3,5')->name('user.2fa.verify');

Route::get('products', 'ProductController@index')->name('product.index');
Route::get('products/{product:slug}', 'ProductController@show')->name('product.show');
// Route::get('products/{product:slug}/download/postman', 'ProductController@downloadPostman')->name('product.download.postman');
Route::get('products/{product:slug}/download/swagger', 'ProductController@downloadSwagger')->name('product.download.swagger');

Route::get('categories/{category:slug}', 'CategoryController@show')->name('category.show');

Route::get('bundles/{bundle:slug}', 'BundleController@show')->name('bundle.show');

Route::get('getting-started', 'GettingStartedController@index')->name('doc.index');
Route::get('getting-started/{content:slug}', 'GettingStartedController@show')->name('doc.show');

Route::get('faq', 'FaqController@index')->name('faq.index');
Route::get('faq/{faq:slug}', 'FaqController@show')->name('faq.show');

Route::get('contact', 'ContactController@index')->name('contact.index');
Route::post('contact', 'ContactController@send')->middleware('throttle:1,5')->name('contact.send');

Auth::routes(['verify' => true]);

Route::get('{content:slug}', 'ContentController@show')->name('page.show');
