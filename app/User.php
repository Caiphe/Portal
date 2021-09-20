<?php

namespace App;

use App\Role;
use App\Country;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
	use Notifiable;

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

	public function hasPermissionTo($permission)
	{
		$userPermissions = $this->roles->map->permissions
			->flatten()
			->pluck('name')
			->unique()
			->toArray();

		if (is_array($permission)) {
			return !is_null($this['2fa']) && !array_diff($permission, $userPermissions);
		}

		return !is_null($this['2fa']) && in_array($permission, $userPermissions);
	}

	public function hasAnyPermissionTo($permission)
	{
		$userPermissions = $this->roles->map->permissions
			->flatten()
			->pluck('name')
			->unique()
			->toArray();

		if (is_array($permission)) {
			return !is_null($this['2fa']) && count(array_intersect($permission, $userPermissions)) > 0;
		}

		return !is_null($this['2fa']) && in_array($permission, $userPermissions);
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

	public function getDeveloperAppsCount() {
	    return App::where('developer_id', $this->developer_id)->get()->count();
    }

    public function getApps($countryCodeFilter = '')
    {
        if (!empty($countryCodeFilter) && $countryCodeFilter !== 'all') {
            $apps = App::where('developer_id', $this->developer_id)->where('country_code', $countryCodeFilter)->get();
        } else {
            $apps = App::where('developer_id', $this->developer_id)->get();
        }
        return $apps;
    }
}
