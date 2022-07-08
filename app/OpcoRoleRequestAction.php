<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcoRoleRequestAction extends Model
{
    use HasFactory;
    protected $table = 'opco_role_request_action';
    protected $guarded = [];
}
