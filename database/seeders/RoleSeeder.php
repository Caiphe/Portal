<?php

namespace Database\Seeders;

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            ['name' => 'admin', 'label' => 'Admin'],
            ['name' => 'developer', 'label' => 'Developer'],
            ['name' => 'opco', 'label' => 'Opco'],
            ['name' => 'internal', 'label' => 'Internal'],
            ['name' => 'content_creator', 'label' => 'Content creator'],
            ['name' => 'private', 'label' => 'Private'],
            ['name' => 'team_admin', 'label' => 'Team admin'],
            ['name' => 'team_user', 'label' => 'Team user'],
        ]);
    }
}
