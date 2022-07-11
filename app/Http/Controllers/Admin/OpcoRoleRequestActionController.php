<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Country;
use App\OpcoRoleRequest;
use App\OpcoRoleRequestAction;
use App\Mail\OpcoAdminRoleDenial;
use App\Mail\OpcoAdminRoleApproved;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Admin\OpcoRoleRequestApprovalFormRequest;

class OpcoRoleRequestActionController extends Controller
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

        $user->roles()->syncWithoutDetaching(3);
        $user->responsibleCountries()->syncWithoutDetaching($requestCountryCodes);
        
        $countryApproved = Country::whereIn('code', $requestCountryCodes)->pluck('name');
        Mail::to($user->email)->send(new OpcoAdminRoleApproved($countryApproved));

        OpcoRoleRequestAction::create([
            'request_id' => $requestId,
            'approved_by' => $admin->id,
            'approved' => true,
            'message' => $data['message']

        ]);

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

        OpcoRoleRequestAction::create([
            'request_id' => $requestId,
            'approved_by' => $admin->id,
            'approved' => false,
            'message' => $data['message']

        ]);

        return json_encode(array(
            "statusCode"=>200
        ));

    }
}
