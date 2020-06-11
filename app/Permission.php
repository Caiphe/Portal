<?php

namespace App;

use App\Role;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
