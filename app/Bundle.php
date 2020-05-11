<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Product;
use App\KeyFeature;

class Bundle extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $primaryKey = "bid";
    public $incrementing = false;

    public function setDisplayNameAttribute($value) {
        $this->attributes['display_name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function products() {
        return $this->belongsToMany(Product::class, "bundle_product", "bundle_bid", "product_pid");
    }

    public function content() {
        return $this->belongsToMany(Content::class, "bundle_content", "bundle_bid");
    }

    public function keyFeatures() {
        return $this->belongsToMany(KeyFeature::class, "bundle_key_feature", "bundle_bid", "key_feature_id");
    }
}
