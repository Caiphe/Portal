<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    /**
     * Get the list of developers that are registered in the system and have developer ID from APIGEE
     * @param Request $request
     * @return mixed
     */
    public function getDevelopers(Request $request)
    {
        return User::where('email', 'like', '%'.$request->email.'%')
            ->where('email_verified_at', '!=', null)
            ->where('developer_id', '!=', null)
            ->get('email');
    }
}
