<?php

namespace App\Observers;

use App\Services\ApigeeService;
use App\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        $email = $user->getOriginal('email');

        ApigeeService::post("developers/{$email}", [
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
        ]);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
