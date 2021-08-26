<?php

namespace Database\Seeders;

use App\{ User, Team };
use Illuminate\Database\Seeder;

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::find(4);

        $firstTeam = Team::create([
            'name' => 'Plus Narrative',
            'url' => 'https://plusnarrative.com/',
            'contact' => 'info@plusnarrative.com',
            'country' => 'South Africa',
            'logo' => '/teams/logo/team-logo.png',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'owner_id' => $user->id
        ]);

        $secondTeam = Team::create([
            'name' => 'Mtn Group',
            'url' => 'https://mtn.co.za/',
            'contact' => 'info@mtn.co.za',
            'country' => 'South Africa',
            'logo' => '/teams/logo/company-logo.png',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'owner_id' => $user->id
        ]);

        $user->attachTeam($firstTeam);
        $user->attachTeam($secondTeam);
    }
}
