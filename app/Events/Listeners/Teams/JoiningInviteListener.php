<?php

namespace App\Events\Listeners\Teams;

use App\Mail\Teams\InternalInvite;

use App\Events\Listeners\Concerns\TeamsEvents;

use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Events\UserJoinedTeam;

/**
 * Class JoiningInviteListener
 *
 * @package App\Events\Listeners\Teams
 */
class JoiningInviteListener
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
     * @param  UserJoinedTeam  $event
     * @return void
     */
    public function handle(UserJoinedTeam $event)
    {
        $user = $this->getJoiningUser( $event );

        Mail::to($user->email)
            ->send( new InternalInvite( $this->getTeam($event), $user ) );
    }
}
