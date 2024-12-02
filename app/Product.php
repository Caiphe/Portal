<?php

namespace App;

use App\App;
use App\Log;
use App\Content;
use App\Country;
use App\Category;
use App\KeyFeature;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
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
        return $query->whereAccess("public");
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

        // if ($user && $user->hasPermissionTo('view_internal_products')) {
        //     return $query->isPublicWithInternal()->getEnvironment($environment);
        // }

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

    public function scopeByLocations($query, array $locations)
    {
        $query->where(function ($q) use ($locations) {
            foreach ($locations as $loc) {
                $q->orWhereRaw("find_in_set('$loc',locations)");
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

    /**
     * Get the products Log
     */
    public function logs()
    {
        return $this->morphMany(Log::class, 'logable');
    }

    public function apps()
    {
        return $this->belongsToMany(App::class, "app_product", "product_pid", "app_aid")->withPivot('status', 'actioned_at', 'status_note');
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

    public function getNotesAttribute()
    {
        $notes = $this->pivot->status_note ?? 'No notes at the moment';
        if ($notes === 'No notes at the moment') return $notes;

        $notes = str_replace("\n", "<br>", $notes);
        $notes = preg_replace('/\b(\d\d [a-zA-Z]+ \d\d\d\d)\b/', '<strong>$1</strong>', $notes);
        $notes = preg_replace('/\b(Notes)\b/', '<strong>$1</strong>', $notes);

        return $notes;
    }
}
