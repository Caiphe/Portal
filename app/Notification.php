<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'notification',
        'read_at'
    ];

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, h:m');
    }

    protected $appends = ['formattedDate'];
}
