<?php

namespace App;

use App\App;
use App\Content;
use App\Category;
use App\Country;
use App\KeyFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $primaryKey = "pid";
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'pid' => 'string',
        'attributes' => 'array',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function setCategoryAttribute($value)
    {
        $this->attributes['category'] = $value;
        $this->attributes['category_slug'] = Str::slug($value);
    }

    public function scopeHasSwagger($query)
    {
        return $query->whereNotNull('swagger');
    }

    public function scopeIsPublic($query)
    {
        return $query->hasSwagger()->whereAccess("public");
    }

    public function scopeHasCategory($query, $category)
    {
        return $query->isPublic()->where('category_slug', $category);
    }

    public function scopeIsPublicWithInternal($query)
    {
        return $query->hasSwagger()->where(function ($query) {
            $query->where('access', 'public')
                ->orWhere('access', 'internal');
        });
    }

    public function scopeIsPublicWithPrivate($query)
    {
        return $query->hasSwagger()->where(function ($query) {
            $query->where('access', 'public')
                ->orWhere('access', 'private');
        });
    }

    public function scopeBasedOnUser($query, $user, $environment = 'prod')
    {
        if ($user && $user->hasPermissionTo(['view_internal_products', 'view_private_products'])) {
            return $query->isPublicWithPrivate()->orWhere(fn($q) => $q->isPublicWithInternal())->getEnvironment($environment);
        }

        if ($user && $user->hasPermissionTo('view_internal_products')) {
            return $query->isPublicWithInternal()->getEnvironment($environment);
        }

        if ($user && $user->hasPermissionTo('view_private_products')) {
            return $query->isPublicWithPrivate()->getEnvironment($environment);
        }

        return $query->isPublic()->getEnvironment($environment);
    }

    public function scopeGetEnvironment($query, $environment)
    {
        $envs = explode(',', $environment);

        $query->where(function ($q) use ($envs) {
            foreach ($envs as $env) {
                $q->orWhereRaw("find_in_set('$env',environments)");
            }
        });

        return $query;
    }

    public function scopeByResponsibleCountry($query, $user)
    {
        if ($user->hasRole('admin')) return $query;

        $countriesResponsibleFor = $user->responsibleCountries()->pluck('code');

        return $query->whereHas('countries', function ($q) use ($countriesResponsibleFor) {
            $q->whereIn('country_code', $countriesResponsibleFor);
        });
    }

    /**
     * Get the products content.
     */
    public function content()
    {
        return $this->morphMany(Content::class, 'contentable');
    }

    public function apps()
    {
        return $this->belongsToMany(App::class, "app_product", "product_pid", "app_aid")->withPivot('status', 'actioned_at');
    }

    public function keyFeatures()
    {
        return $this->belongsToMany(KeyFeature::class, "key_feature_product", "product_pid", "key_feature_id");
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }
}
