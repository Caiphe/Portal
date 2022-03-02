<?php

namespace App\Listeners;

use App\Services\ApigeeUserService;

class VerifiedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;

        if (!is_null($user->developer_id)) return;

        ApigeeUserService::setupUser($user);
    }
}
