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
        $data['countries'] = implode(',', $countries);
        $data['user_id'] = $user->id;
        $requestCountryCodes = explode(',', $data['countries']);

        OpcoRoleRequest::create($data);
        $countries = Country::whereIn('code', $requestCountryCodes)->pluck('name')->toArray();
        $adminUsers = User::whereHas('roles', fn ($q) => $q->where('name', 'Admin'))->pluck('email')->toArray();

        foreach($adminUsers as $admin){
            Mail::to($admin)->send(new OpcoAdminRoleRequest($user, $countries));
        }
        
        return response()->json(['success' => true, 'code' => 200], 200);
    }
}
