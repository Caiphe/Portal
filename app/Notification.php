<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'notification',
        'read_at'
    ];

    public function getCreatedAtAttribute($value)
    {
        $createdAt = Carbon::parse($value);
        
        return $createdAt->format('M d, h:i');
    }

}
