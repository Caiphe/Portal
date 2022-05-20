<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'logable_id',
        'logable_type',
        'message'
    ];

    public function logable()
    {
        return $this->morphTo();
    }
}
