<?php

namespace App;

use App\Content;
use App\KeyFeature;
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
    protected $primaryKey = "pid";
    public $incrementing = false;

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
        return $query->hasSwagger()->whereAccess("public");
    }

    public function scopeGetEnvironment($query, $environment)
    {
        return $query
            ->isPublic()
            ->hasSwagger()
            ->whereRaw("find_in_set('$environment',environments)");
    }

    public function content()
    {
        return $this->belongsToMany(Content::class, "content_product", "product_pid");
    }

    public function keyFeatures()
    {
        return $this->belongsToMany(KeyFeature::class, "key_feature_product", "product_pid", "key_feature_id");
    }
}
