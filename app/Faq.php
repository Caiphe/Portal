<?php

namespace App;

use App\FaqFeedback;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $guarded = [];

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
