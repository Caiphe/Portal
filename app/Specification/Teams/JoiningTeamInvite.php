<?php

namespace App\Specification\Teams;

use Illuminate\Database\Eloquent\Model;

/**
 * Class JoiningTeamInvite
 *
 * @package App\Specification\Teams
 */
class JoiningTeamInvite implements TeamSpecification
{
    public function matches(Model $subject, array $invitees)
    {
        $invitedMembers = \DB::table('users')->whereIn('email', $invitees)->get();

        return count($invitees) === $invitedMembers->count();
    }

    public function asQuery($query, array $invitees)
    {
        return $query->whereIn('email', $invitees);
    }
}
