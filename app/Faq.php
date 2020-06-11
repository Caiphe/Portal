<?php

namespace App;

use App\Category;
use App\FaqFeedback;
use App\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Faq extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function setQuestionAttribute($value)
    {
        $this->attributes['question'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function feedback()
    {
        return $this->hasMany(FaqFeedback::class);
    }

    public function isHelpful($type, $feedback = '')
    {
        $this->feedback()->create([
            'faq_id' => $this->id,
            'type' => $type,
            'feedback' => $feedback
        ]);
    }

    /**
     * Get all of the tags for the post.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
