<?php

namespace App\Factory\Teams;

use App\Team;
use App\User;

/**
 * Class TeamFactory
 *
 * @package App\Factory\Teams
 */
class TeamFactory
{
    /**
     * Locally creates a Team within storage
     *
     * @param User $user
     * @param array $data
     * @return Team
     */
    public static function createUserTeam(User $user, array $data): Team
    {
        return Team::create([
            'name' => $data['name'],
            'url' => $data['url'],
            'contact' => $data['contact'],
            'country' => $data['country'],
            'description' => $data['description'],
            'logo' => $data['filename'],
            'owner_id' => $user->getKey()
        ]);
    }
}
