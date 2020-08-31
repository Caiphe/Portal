<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [
		// 'App\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();

		Gate::define('view-dashboard', function ($user) {
			return $user->hasPermissionTo('view_dashboard_products');
		});

		Gate::define('view-admin', function ($user) {
			return $user->hasAnyPermissionTo([
				'administer_products',
				'view_dashboard_products',
				'administer_users',
				'administer_content',
				'administer_dashboard_products',
			]);
		});

		Gate::define('administer-products', function ($user) {
			return $user->hasPermissionTo('administer_products');
		});

		Gate::define('administer-users', function ($user) {
			return $user->hasPermissionTo('administer_users');
		});

		Gate::define('administer-content', function ($user) {
			return $user->hasPermissionTo('administer_content');
		});

		Gate::define('administer-dashboard', function ($user) {
			return $user->hasPermissionTo('administer_dashboard_products');
		});
	}
}
