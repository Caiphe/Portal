<?php

namespace App\Events\Listeners\Teams;

use App\Team;

use App\Mail\Invites\RemoveUser;

use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Events\UserLeftTeam;

/**
 * Class UserLeftTeamListener
 *
 * @package App\Events\Listeners\Teams
 */
class UserLeftTeamListener
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
     * @param UserLeftTeam $event
     * @return void
     */
    public function handle(UserLeftTeam $event)
    {
        $user = $event->getUser();
        $team = Team::findOrFail($event->getTeamId());

        Mail::to($user->email)->send(new RemoveUser($team, $user));
    }
}
