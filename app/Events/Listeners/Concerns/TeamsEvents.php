<?php

namespace App\Events\Listeners\Concerns;

use App\Team;

/**
 * Trait TeamsEvents
 *
 * @package App\Events\Listeners\Concerns
 */
trait TeamsEvents
{
    /**
     * @param $event
     * @return Team
     */
    public function getTeam($event)
    {
        return Team::find($event->getTeamId());
    }

    /**
     * @param $event
     * @return mixed
     */
    public function getJoiningUser($event)
    {
        return static::getUser( $event );
    }

    /**
     * @param $event
     * @return mixed
     */
    public function getLeavingUser($event)
    {
        return static::getUser( $event );
    }

    /**
     * @param $event
     * @return mixed
     */
    public function getRemindedUser($event)
    {
        return $event->getInvite()->user;
    }

    /**
     * @param $event
     * @return mixed
     */
    public static function getUser($event)
    {
        return $event->getUser();
    }
}
