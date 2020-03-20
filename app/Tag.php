<?php

namespace App;

use App\Casts\Slug;
use App\Content;
use App\Faq;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'slug' => Slug::class,
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
    
    /**
     * Get all of the Faqs that are assigned this tag.
     */
    public function faqs()
    {
        return $this->morphedByMany(Faq::class, 'taggable');
    }
    
    /**
     * Get all of the Content that are assigned this tag.
     */
    public function contents()
    {
        return $this->morphedByMany(Content::class, 'taggable');
    }
}
