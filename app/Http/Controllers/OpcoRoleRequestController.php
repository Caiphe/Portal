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

        $countries = Country::whereIn('code', $requestCountryCodes)->pluck('name')->toArray();
        $adminUsers = User::whereHas('roles', fn ($q) => $q->where('name', 'Admin'))->pluck('email')->toArray();
       
        abort_if(OpcoRoleRequest::whereDoesntHave('action')->where('user_id', $data['user_id'])->where('countries', $requestCountryCodes)->first(), 412, 'You have already requested an Opco role for this country');
       
        OpcoRoleRequest::create($data);

        $requestedCountry = Country::where('code', $requestCountryCodes)->first();
        $requestedCountryOpcoEmail = $requestedCountry->opcoUser->flatten()->pluck('email')->toArray();

        if($requestedCountryOpcoEmail){
            Mail::bcc($requestedCountryOpcoEmail)->send(new OpcoAdminRoleRequest($user, $countries));
            return response()->json(['success' => true, 'code' => 200], 200);
        }

        Mail::bcc($adminUsers)->send(new OpcoAdminRoleRequest($user, $countries));
        return response()->json(['success' => true, 'code' => 200], 200);
    }
}
