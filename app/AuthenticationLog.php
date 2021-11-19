<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthenticationLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'login_at',
        'login_successful',
        'logout_at',
        'cleared_by_user',
        'location',
    ];

    protected $casts = [
        'cleared_by_user' => 'boolean',
        'location' => 'array',
        'login_successful' => 'boolean',
    ];

    protected $dates = [
        'login_at',
        'logout_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
