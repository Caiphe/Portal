<?php

namespace App;

use Mpociot\Teamwork\TeamworkTeam;

/**
 * Class Team
 *
 * @package App
 */
class Team extends TeamworkTeam
{
    protected $fillable = [
        'name',
        'owner_id',
        'url',
        'contact',
        'country',
        'logo',
        'description'
    ];
}
