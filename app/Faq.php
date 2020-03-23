<?php

namespace App;

use App\Casts\Slug;
use App\FaqFeedback;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'slug' => Slug::class,
    ];

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
}
