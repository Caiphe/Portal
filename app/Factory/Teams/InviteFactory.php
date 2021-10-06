<?php

namespace App\Factory\Teams;

use Mpociot\Teamwork\TeamInvite;

/**
 * Class InviteFactory
 *
 * @package App\Factory\Teams
 */
class InviteFactory
{
    /**
     * @param array $data
     * @return TeamInvite
     */
    public static function createFromInput(array $data)
    {
        extract($data);

        $keyGenerator = function() {
            return md5(uniqid(microtime()));
        };

        TeamInvite::unguard();

        $invite = new TeamInvite();

        $invite->user_id = $user_id;
        $invite->team_id = $team_id;
        $invite->type = isset($type) ? $type : 'invite';
        $invite->email = $email;
        $invite->accept_token = $keyGenerator();
        $invite->deny_token = $keyGenerator();

        $invite->save();

        TeamInvite::reguard();

        return $invite;
    }

    /**
     * @param array $data
     * @return TeamInvite
     */
    public static function createFromInvite(array $data)
    {
        extract($data);

        TeamInvite::unguard();

        $invite = TeamInvite::where([
            'user_id' => $user_id,
            'team_id' => $team_id,
            'type' => 'invite'
        ]);

        $invite->type = 'request';

        $invite->save();

        TeamInvite::reguard();

        return $invite;
    }
}
