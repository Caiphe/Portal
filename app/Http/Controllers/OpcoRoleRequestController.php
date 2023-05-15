<?php

namespace App\Http\Controllers;

use App\User;
use App\Country;
use App\OpcoRoleRequest;
use App\Mail\OpcoAdminRoleRequest;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\OpcoRoleRequestFormRequest;

class OpcoRoleRequestController extends Controller
{
    public function store(OpcoRoleRequestFormRequest $request){
        $user = auth()->user();
        $data = $request->validated();
        $countries = $data['countries'];

        abort_if(count($countries) > 1, 424, "Only one country allowed per request");

        $data['countries'] = implode(',', $countries);
        $data['user_id'] = $user->id;
        $requestCountryCodes = explode(',', $data['countries']);
		$usersCountries = $user->countries;
        
        OpcoRoleRequest::create($data);
        $countries = Country::whereIn('code', $requestCountryCodes)->pluck('name')->toArray();
        $adminUsers = User::whereHas('roles', fn ($q) => $q->where('name', 'Admin'))->pluck('email')->toArray();

        if($usersCountries){
			$opcoEmails = $usersCountries->load('opcoUser')->pluck('opcoUser')->flatten()->pluck('email')->unique()->values();

            Mail::bcc($opcoEmails)->send(new OpcoAdminRoleRequest($user, $countries));
			return response()->json(['success' => true, 'code' => 200], 200);  
        }

        Mail::bcc($adminUsers)->send(new OpcoAdminRoleRequest($user, $countries));
        return response()->json(['success' => true, 'code' => 200], 200);
    }
}
