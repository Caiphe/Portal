<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Product;
use App\KeyFeature;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bundle extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $primaryKey = "bid";
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'bid' => 'string'
    ];

    public function setDisplayNameAttribute($value)
    {
        $this->attributes['display_name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, "bundle_product", "bundle_bid", "product_pid");
    }

    /**
     * Get the products content.
     */
    public function content()
    {
        return $this->morphMany(Content::class, 'contentable');
    }

    public function keyFeatures()
    {
        return $this->belongsToMany(KeyFeature::class, "bundle_key_feature", "bundle_bid", "key_feature_id");
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeByResponsibleCountry($query, $user)
    {
        if($user->hasRole('admin')) return $query;

        $countriesResponsibleFor = $user->responsibleCountries()->pluck('code');

        return $query->whereHas('products.countries', function ($q) use ($countriesResponsibleFor) {
            $q->whereIn('country_code', $countriesResponsibleFor);
        });
    }
}
