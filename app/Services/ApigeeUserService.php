<?php

namespace App\Services;

use App\Services\ApigeeService;
use App\User;

class ApigeeUserService
{
	/**
	 * Set up a user on Apigee. If one already exists get the developer id. Save the developer id.
	 *
	 * @param      \App\User  $user   The user
	 * 
	 * @return     void
	 */
	public static function setupUser(User $user): void
    {
        $apigeeDeveloper = ApigeeService::post('developers', [
            "email" => $user->email,
            "firstName" => $user->first_name,
            "lastName" => $user->last_name,
            "userName" => $user->first_name . $user->last_name,
            "attributes" => [
                [
                    "name" => "MINT_DEVELOPER_LEGAL_NAME",
                    "value" => $user->first_name . " " . $user->last_name
                ],
                [
                    "name" => "MINT_BILLING_TYPE",
                    "value" => "PREPAID"
                ]
            ]
        ])->json();

        // check if developer already exists in apigee but does not have developer id in portal database
        if(isset($apigeeDeveloper['code'])){
            $findDeveloper = ApigeeService::get('developers/'. $user->email);
            $user->update(['developer_id' => $findDeveloper['developerId']]);
        }

        // if developer does not exist
        if(!$user->developer_id && isset($apigeeDeveloper['developerId'])){
            $user->update(['developer_id' => $apigeeDeveloper['developerId']]);
        }

    }
}
