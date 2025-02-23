<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcoRoleRequestAction extends Model
{
    use HasFactory;
    protected $table = 'opco_role_request_action';
    protected $fillable = [
        'request_id',
        'approved_by',
        'approved',
        'message'
    ];

    public function OpcoRoleRequest()
    {
        return $this->belongsTo(OpcoRoleRequest::class, 'request_id', 'id');
    }
}
