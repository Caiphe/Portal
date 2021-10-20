<?php

namespace App\Events\Listeners\Teams;

use App\Mail\Teams\PendingInvite;

use App\Events\Listeners\Concerns\TeamsEvents;

use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Events\UserInvitedToTeam;

/**
 * Class RemindingInviteListener
 *
 * @package App\Events\Listeners\Teams
 */
class RemindingInviteListener
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
     * @param  UserInvitedToTeam  $event
     * @return void
     */
    public function handle(UserInvitedToTeam $event)
    {
        $user = $this->getRemindedUser( $event );

        Mail::to($user->email)
            ->send( new PendingInvite( $this->getTeam($event), $user, $event->getInvite() ));
    }
}
