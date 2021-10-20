<?php

namespace App\Events\Listeners\Teams;

use App\Mail\Teams\LeavingInvite;

use App\Events\Listeners\Concerns\TeamsEvents;

use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Events\UserLeftTeam;

/**
 * Class LeavingInviteListener
 *
 * @package App\Events\Listeners\Teams
 */
class LeavingInviteListener
{
    use TeamsEvents;

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
     * @param UserLeftTeam $event
     * @return void
     */
    public function handle(UserLeftTeam $event)
    {
        $user = $this->getLeavingUser( $event );

        Mail::to($user->email)
            ->send( new LeavingInvite( $this->getTeam($event), $user ) );
    }
}
