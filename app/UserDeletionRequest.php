<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeletionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'countries',
        'requested_by',
        'user_id',
        'user_email',
        'user_name',
        'approved_by',
        'approved_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
