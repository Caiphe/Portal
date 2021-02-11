<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $primaryKey = "cid";
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'cid' => 'string',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function setTitleAttribute($value)
    {
        $categorySlug = Str::slug($value);
        if (!\Storage::disk('app')->exists('images/icons/' . $categorySlug . '.svg')) {
            \Storage::disk('app')->copy('images/icons/misc.svg', 'images/icons/' . $categorySlug . '.svg');
        }

        $this->attributes['title'] = $value;
        $this->attributes['slug'] = $categorySlug;
        $this->attributes['cid'] = $categorySlug;
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function bundles()
    {
        return $this->hasMany(Bundle::class);
    }

    /**
     * Get the products content.
     */
    public function content()
    {
        return $this->morphMany(Content::class, 'contentable');
    }
}
