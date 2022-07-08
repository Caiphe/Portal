<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use Carbon\Carbon;
use App\OpcoRoleRequest;
use App\Mail\OpcoAdminRoleDenial;
use App\Mail\OpcoAdminRoleApproved;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Admin\OpcoRoleRequestApprovalFormRequest;
use DB;

class OpcoRoleRequestApprovalController extends Controller
{
    public function approve(OpcoRoleRequestApprovalFormRequest $request)
    {
        $admin = auth()->user();
        $countryApproved = [];
        $data = $request->validated();
        $requestId = $data['request_id'];

        $roleRequest = OpcoRoleRequest::where('id', $requestId)->first();
        $user = $roleRequest->user;
        $requestCountryCodes = explode(',', $roleRequest->countries);

        $userRoleIds = [];
        dd($user->roles);
        for($i = 0 ; $i < count($user->roles); $i++){
            $userRoleIds[] = $user->roles[$i]->id;
        }

        if(!in_array(3, $userRoleIds)){
            $user->roles()->attach(3);
        }

        $userCountriesCode = [];
        for($i = 0 ; $i < count($user->responsibleCountries); $i++){
            $userCountriesCode[] = $user->responsibleCountries[$i]->code;
        }

        $roleRequest->update(['status' => 'approved']);

        for($i =0; $i < count($requestCountryCodes); $i++){
            $countryApproved[] = Country::where('code', $requestCountryCodes[$i])->pluck('name')->first();
            $user->responsibleCountries()->attach($requestCountryCodes[$i]);
        }
        
        Mail::to($user->email)->send(new OpcoAdminRoleApproved($countryApproved));

        DB::insert('insert into approve_opco_role_request (request_id, approved_by, approved,created_at, message) 
            values (?, ?, ?, ?, ?)', [$requestId, $admin->id, 1, Carbon::now(), $data['message']]
        );

        return json_encode(array(
            "statusCode"=>200
        ));
    }

    public function deny(OpcoRoleRequestApprovalFormRequest $request)
    {
        $admin = auth()->user();
        $data = $request->validated();
        $requestId = $data['request_id'];

        $roleRequest = OpcoRoleRequest::where('id', $requestId)->first();
        Mail::to($roleRequest->user->email)->send(new OpcoAdminRoleDenial($data));

        DB::insert('insert into approve_opco_role_request (request_id, approved_by, approved,created_at, message) 
            values (?, ?, ?, ?, ?)', 
            [$requestId,  $admin->id, 0, Carbon::now(), $data['message']]);

        return json_encode(array(
            "statusCode"=>200
        ));

    }
}
