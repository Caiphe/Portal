<?php

namespace App\Events\Listeners\Teams;

use App\Team;
use App\Mail\Invites\ExistingMember;

use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Events\UserJoinedTeam;

/**
 * Class JoinedTeamListener
 *
 * @package App\Events\Listeners\Teams
 */
class JoinTeamInviteListener
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
     * @param  UserJoinedTeam  $event
     * @return void
     */
    public function handle(UserJoinedTeam $event)
    {
        $user = $event->getUser();
        $team = Team::findOrFail($event->getTeamId());

        Mail::to($user->email)->send(new ExistingMember($team, $user));
    }
}
