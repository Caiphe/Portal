<?php

namespace App\Providers;

use App\App;
use App\Team;
use App\User;
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
			return $user->hasPermissionTo('view_admin_backend');
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

		Gate::define('access-own-app', function ($user, App $app) {
			return $app->developer_id === $user->developer_id;
		});

		Gate::define('access-hidden-products', function ($user) {
			return $user->hasAnyPermissionTo(['view_internal_products', 'view_private_products']);
		});

		Gate::define('access-internal-products', function ($user) {
			return $user->hasPermissionTo('view_internal_products');
		});

		Gate::define('access-private-products', function ($user) {
			return $user->hasPermissionTo('view_private_products');
		});

        Gate::define('administer-team', function ($user, $teamId) {
			$team = Team::find($teamId);
            return $user->hasTeamRole($team, 'team_admin') || $user->isOwnerOfTeam($team);
        });

        Gate::define('administer-team-by-owner', function ($user, Team $team) {
            return $user->isOwnerOfTeam($team);
        });

        Gate::define('administer-own-team', function ($user, Team $team) {
            return $team->hasUser($user) || $user->hasTeamRole($team, 'team_admin') || $user->isOwnerOfTeam($team);
        });
	}
}
