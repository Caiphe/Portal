<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeletionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'countries',
        'request_by',
        'user_id',
        'approved_by',
        'approved_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
