<?php

namespace App\Concerns\Teams;

use App\Team;
use App\User;

use Mpociot\Teamwork\TeamInvite;

/**
 * Trait InviteRequests
 *
 * @package App\Concerns\Teams
 */
trait InviteRequests
{
    /**
     * @param Team $team
     * @param string $memberEmail
     */
    public function createTeamInvite(Team $team, string $memberEmail, $type = 'invite')
    {
        $createToken = function(){
            return md5(uniqid(microtime()));
        };

        $invite = new TeamInvite();

        $invite->user_id = $team->owner->id;
        $invite->team_id = $team->id;
        $invite->type = $type;
        $invite->email = $memberEmail;
        $invite->accept_token = $createToken();
        $invite->deny_token = $createToken();

        $invite->save();

        return $invite;
    }

    /**
     * @param string $memberEmail
     * @return User
     */
    public function getTeamUserByEmail( string $memberEmail)
    {
        return User::where('email', $memberEmail)->first();
    }

    /**
     * @param $userId
     * @return User
     */
    public static function getTeamUser($userId)
    {
        return User::find($userId);
    }

    /**
     * @param $teamId
     * @return Team
     */
    public static function getTeam($teamId)
    {
        return Team::find($teamId);
    }
}
