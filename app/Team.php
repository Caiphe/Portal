<?php

namespace App;

use Illuminate\Support\Facades\Config;
use Mpociot\Teamwork\TeamworkTeam;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Team
 *
 * @package App
 */
class Team extends TeamworkTeam
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'owner_id',
        'url',
        'contact',
        'country',
        'logo',
        'description'
    ];

    public function teamCountry()
    {
        return $this->belongsTo(Country::class, 'country', 'code');
    }

    public function apps()
    {
        return $this->hasMany(App::class);
    }

    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Config::get('teamwork.user_model'), Config::get('teamwork.team_user_table'), 'team_id', 'user_id')->withPivot('role_id')->withTimestamps();
    }

    public function getUsernameAttribute()
    {
        return Str::slug($this->name);
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getEmailAttribute()
    {
        return $this->owner->email ?? '';
    }
}
