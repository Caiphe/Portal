<?php

namespace App;

use App\Country;
use App\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'password'];

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
        'email_verified_at' => 'datetime'
    ];

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     *
     * @var array
     */
    protected $appends = ['profile_picture'];


    public function roles()
    {
        return $this->belongsToMany(Role::class);
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

    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getProfilePictureAttribute()
    {
        $img = base64_encode('jsklaf88sfjdsfjl' . $this->id);
        return "/storage/profile/$img.png";
    }
}
