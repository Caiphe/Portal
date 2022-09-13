<?php

namespace App\Http\Controllers;

use App\User;
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
        OpcoRoleRequest::create($data);

        $adminUsers = User::whereHas('roles', fn ($q) => $q->where('name', 'Admin'))
					  ->pluck('email')->toArray();

        foreach($adminUsers as $admin){
            Mail::to($admin)->send(new OpcoAdminRoleRequest($user));
        }
        
        return response()->json(['success' => true, 'code' => 200], 200);
    }
}
