<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\OpcoRoleRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = $request->user();
        $countries = Country::all();
        $opcoCountries = $currentUser->responsibleCountries()->pluck('code')->toArray();

        $opcoRequests = OpcoRoleRequest::whereDoesntHave('action')->with('user.countries')->orderBy('id', 'DESC')->get();

        if(!$currentUser->hasRole('admin') && $currentUser->hasRole('opco')){
            
            $opcoRequests = $opcoRequests->filter(function($item) use ($opcoCountries) {
                return count(array_intersect(explode(',', $item->countries), $opcoCountries)) > 0;
            });
        }

        return view('templates.admin.tasks.index', [
            'opco_role_requests' => $opcoRequests,
            'countries' => $countries
        ]);
    }
}
