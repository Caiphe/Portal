<?php

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = User::create([
            'first_name' => 'Wesley',
            'last_name' => 'Martin',
            'email' => 'wes@plusnarrative.com',
            'password' => bcrypt('devport')
        ]);

        $developerUser = User::create([
            'first_name' => 'developer',
            'last_name' => 'User',
            'email' => 'developer@user.com',
            'password' => bcrypt('devport')
        ]);

        $opcoAdminUser = User::create([
            'first_name' => 'Opco Admin',
            'last_name' => 'User',
            'email' => 'opco-admin@user.com',
            'password' => bcrypt('devport')
        ]);

        $opcoUser = User::create([
            'first_name' => 'Opco',
            'last_name' => 'User',
            'email' => 'opco@user.com',
            'password' => bcrypt('devport')
        ]);

        $administerContentPermission = Permission::create([
            'name' => "administer_content",
            'label' => "Administer content"
        ]);

        $administerProductsPermission = Permission::create([
            'name' => "administer_products",
            'label' => "Administer products"
        ]);

        $administerUsersPermission = Permission::create([
            'name' => "administer_users",
            'label' => "Administer users"
        ]);

        $createAppPermission = Permission::create([
            'name' => "create_app",
            'label' => "Create an app"
        ]);

        $administerProductsPermission = Permission::create([
            'name' => "administer_products",
            'label' => "Administer user created products"
        ]);

        $viewProductsPermission = Permission::create([
            'name' => "view_products",
            'label' => "View user created products"
        ]);

        $adminRole = Role::create([
            'name' => "admin",
            'label' => "Admin"
        ]);

        $adminRole->allowTo(Permission::all());
        $adminUser->assignRole($adminRole);

        $developerRole = Role::create([
            'name' => "developer",
            'label' => "Developer"
        ]);

        $developerRole->allowTo('create_app');
        $developerUser->assignRole($developerRole);

        $opcoAdminRole = Role::create([
            'name' => "opco_admin",
            'label' => "Opco Admin"
        ]);

        $opcoAdminRole->allowTo(['create_app', 'administer_products']);
        $opcoAdminUser->assignRole($opcoAdminRole);

        $opcoRole = Role::create([
            'name' => "opco",
            'label' => "Opco"
        ]);

        $opcoRole->allowTo(['create_app', 'view_products']);
        $opcoUser->assignRole($opcoRole);
    }
}
