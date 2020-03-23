<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function scopeHasSwagger($query)
    {
        return $query->whereNotNull('swagger');
    }

    public function scopeIsPublic($query)
    {
        return $query->whereAccess("public");
    }

    public function scopeGetEnvironment($query, $environment)
    {
        return $query
            ->isPublic()
            ->hasSwagger()
            ->whereRaw("find_in_set('$environment',environments)");
    }
}
