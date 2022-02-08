<?php

namespace App;

use App\Role;
use App\Country;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Mpociot\Teamwork\TeamInvite;
use Mpociot\Teamwork\Traits\UserHasTeams;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements MustVerifyEmail
{
	use Notifiable, UserHasTeams;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['first_name', 'last_name', 'email', 'password', 'developer_id', 'email_verified_at', 'profile_picture', '2fa', 'recovery_codes'];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'recovery_codes' => 'array'
	];

	public function roles()
	{
		return $this->belongsToMany(Role::class);
	}

	public function hasRole($role)
	{
		return $this->roles->pluck('name')->contains($role);
	}

	public function assignRole($role)
	{
		if (is_string($role)) {
			$role = Role::whereName($role)->firstOrFail();
		}

		$this->roles()->sync($role, false);
	}

	public function permissions()
	{
		return $this->roles->map->permissions
			->flatten()
			->pluck('name')
			->unique();
	}

	public function teamRole($team)
	{
		return $this->leftJoin('team_user', 'users.id', '=', 'team_user.user_id')
			->leftJoin('teams', 'teams.id', '=', 'team_user.team_id')
			->leftJoin('roles', 'roles.id', '=', 'team_user.role_id')
			->where('users.id', $this->id)
			->where('teams.id', $team->id)
			->select('roles.id', 'roles.label', 'roles.name')
			->first();
	}

	public function belongsToTeam($team)
	{
		if (is_string($team)) {
			$team = Team::find($team);
		}

		return $this->teams->contains($team);
	}

	public function hasTeamRole($team, $role)
	{
		return $this->teamRole($team)->name === $role;
	}

	public function hasTeamPermissionTo($permission, $team)
	{
		$role = $this->teamRole($team);
		$userPermissions = Role::with('permissions')->find($role->id)->permissions
			->flatten()
			->pluck('name')
			->unique()
			->toArray();

		if (is_array($permission)) {
			return !array_diff($permission, $userPermissions);
		}

		return in_array($permission, $userPermissions);
	}

	public function hasPermissionTo($permission)
	{
		$test2FA = skip_2fa() || !is_null($this['2fa']);
		$userPermissions = $this->roles->map->permissions
			->flatten()
			->pluck('name')
			->unique()
			->toArray();

		if (is_array($permission)) {
			return $test2FA && !array_diff($permission, $userPermissions);
		}

		return $test2FA && in_array($permission, $userPermissions);
	}

	public function hasAnyPermissionTo($permission)
	{
		$test2FA = skip_2fa() || !is_null($this['2fa']);
		$userPermissions = $this->roles->map->permissions
			->flatten()
			->pluck('name')
			->unique()
			->toArray();

		if (is_array($permission)) {
			return $test2FA && count(array_intersect($permission, $userPermissions)) > 0;
		}

		return $test2FA && in_array($permission, $userPermissions);
	}

	public function countries()
	{
		return $this->belongsToMany(Country::class);
	}

	public function responsibleCountries()
	{
		return $this->belongsToMany(Country::class, 'country_opco');
	}

	public function responsibleGroups()
	{
		return $this
			->belongsToMany(Product::class, 'group_opco', 'user_id', 'product_group', 'id', 'group')
			->select('group')
			->distinct();
	}

	/**
	 * Get the user's full name.
	 *
	 * @return string
	 */
	public function getFullNameAttribute()
	{
		return "{$this->first_name} {$this->last_name}";
	}

	/**
	 * Get the user slug which is the id.
	 *
	 * @return string
	 */
	public function getSlugAttribute()
	{
		return $this->id;
	}

	public function getRolesListAttribute()
	{
		return $this->roles->pluck('label')->implode(',');
	}

	public function twoFactorStatus()
	{
		return is_null($this['2fa']) ? 'Disabled' : 'Enabled';
	}

	public function getDeveloperAppsCount()
	{
		return App::where('developer_id', $this->developer_id)->get()->count();
	}

	public function getApps($countryCodeFilter = '', $order = 'DESC', $sort = 'name')
	{
		$apps = App::where('developer_id', $this->developer_id)
			->when(!empty($countryCodeFilter) && $countryCodeFilter !== 'all', function ($q) use ($countryCodeFilter) {
				$q->where('country_code', $countryCodeFilter);
			})->orderBy($sort, $order);

		return $apps->get();
	}

	public function teams()
	{
		return $this->belongsToMany(Team::class);
	}

	/**
	 * Wrapper method for "isOwner".
	 *
	 * @return bool
	 */
	public function isTeamOwner(Team $team)
	{
		return $team->owner_id === $this->id;
	}

	/**
	 * Check if User has a Team invite
	 *
	 * @param Team $team
	 * @param string $type
	 * @return bool
	 */
	public function hasTeamInvite(Team $team, $type = 'invite')
	{
		return TeamInvite::where([
			'email' => $this->email,
			'team_id' => $team->id,
			'type' => $type
		])->exists();
	}

	/**
	 * Get a User's team invite
	 *
	 * @param Team $team
	 * @param string $type
	 * @return mixed
	 */
	public function getTeamInvite(Team $team, $type = 'invite')
	{
		return TeamInvite::where([
			'email' => $this->email,
			'team_id' => $team->id,
			'type' => $type
		])->first();
	}

	/**
	 * Get a User's ownership team invite request
	 *
	 * @param Team $team
	 * @return mixed
	 */
	public function getTeamOwernerRequest(Team $team)
	{
		return TeamInvite::where([
			'user_id' => $this->id,
			'team_id' => $team->id,
			'type' => 'ownership'
		])->first();
	}

	public function authentications()
	{
		return $this->hasMany(AuthenticationLog::class)->latest('login_at');
	}

	/**
	 * Send a password reset notification to the user.
	 *
	 * @param  string  $token
	 * @return void
	 */
	public function sendPasswordResetNotification($token)
	{
		$this->notify(new ResetPasswordNotification($token));
	}
}
