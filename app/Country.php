<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $primaryKey = "code";
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'code' => 'string',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function opcoUser()
    {
        return $this->belongsToMany(User::class, 'country_opco');
    }
}
