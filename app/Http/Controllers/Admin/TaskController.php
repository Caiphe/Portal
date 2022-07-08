<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\OpcoRoleRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function index()
    {
        $opcoRequest = OpcoRoleRequest::whereDoesntHave('action')->get();
        $countries = Country::all();
        
        return view('templates.admin.tasks.index', [
            'opco_role_requests' => $opcoRequest,
            'countries' => $countries
        ]);
    }
}
