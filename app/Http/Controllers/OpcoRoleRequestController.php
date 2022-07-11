<?php

namespace App\Http\Controllers;

use App\OpcoRoleRequest;
use App\Mail\OpcoAdminRoleRequest;
use App\Http\Requests\OpcoRoleRequestFormRequest;
use Illuminate\Support\Facades\Mail;

class OpcoRoleRequestController extends Controller
{
    public function store(OpcoRoleRequestFormRequest $request){
        $user = auth()->user();
        $data = $request->validated();
        $countries = $data['countries'];
        $data['countries'] = implode(',', $countries);
        $data['user_id'] = $user->id;
        OpcoRoleRequest::create($data);

		Mail::to(config('mail.mail_to_address'))->send(new OpcoAdminRoleRequest($user));

        return json_encode(array(
            "statusCode"=>200
        ));
    }
}
