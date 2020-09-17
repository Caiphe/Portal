<?php

namespace App;

use App\Country;
use App\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['first_name', 'last_name', 'email', 'password', 'developer_id', 'email_verified_at', 'profile_picture', '2fa'];

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

	public function hasPermissionTo($permission)
	{
		$userPermissions = $this->roles->map->permissions
			->flatten()
			->pluck('name')
			->unique()
			->toArray();

		if (is_array($permission)) {
			return !array_diff($permission, $userPermissions);
		}

		return in_array($permission, $userPermissions);
	}

	public function hasAnyPermissionTo($permission)
	{
		$userPermissions = $this->roles->map->permissions
			->flatten()
			->pluck('name')
			->unique()
			->toArray();

		if (is_array($permission)) {
			return count(array_intersect($permission, $userPermissions)) > 0;
		}

		return in_array($permission, $userPermissions);
	}

	public function countries()
	{
		return $this->belongsToMany(Country::class);
	}

	public function responsibleCountries()
	{
		return $this->belongsToMany(Country::class, 'country_opco');
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
}
