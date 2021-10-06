<?php

namespace App\Events\Listeners\Teams;

use App\Team;
use App\Mail\Invites\InviteNewUser;

use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Events\UserInvitedToTeam;

/**
 * Class UserInvitedToTeamListener
 *
 * @package App\Events\Listeners\Teams
 */
class UserInvitedToTeamListener
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
     * @param  UserInvitedToTeam  $event
     * @return void
     */
    public function handle(UserInvitedToTeam $event)
    {
        $user = $event->getInvite()->user;
        $team = Team::findOrFail($event->getTeamId());

        Mail::to($user->email)->send(new InviteNewUser($team, $user));
    }
}
