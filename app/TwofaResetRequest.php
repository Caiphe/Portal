<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwofaResetRequest extends Model
{
    use HasFactory;
    protected $fillable =['user_id', 'approved_by'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
