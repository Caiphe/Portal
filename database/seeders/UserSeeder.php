<?php

namespace Database\Seeders;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$now = date('Y-m-d H:i:s');

		$adminUser = User::create([
			'first_name' => 'Wesley',
			'last_name' => 'Martin',
			'email' => 'wes@plusnarrative.com',
			'password' => bcrypt('&jklfFI9@bI!'),
			'email_verified_at' => $now,
			'developer_id' => '2a940d6f-22ce-4d58-9844-a5b4bd7fc689',
			'profile_picture' => '/storage/profile/profile-1.svg',
		]);

		$twoFAUser = User::create([
			'first_name' => 'Wesley',
			'last_name' => 'Martin',
			'email' => 'wes+2fa@plusnarrative.com',
			'password' => bcrypt('&jklfFI9@bI!'),
			'email_verified_at' => $now,
			'2fa' => 'VHRDUGYWGZZXWRSJTVM72XDH2GY6PKMX',
			'developer_id' => '2a940d6f-22ce-4d58-9844-a5b4bd7fc689',
			'profile_picture' => '/storage/profile/profile-1.svg',
		]);

		Permission::create([
			'name' => "administer_content",
			'label' => "Administer content",
		]);

		Permission::create([
			'name' => "administer_products",
			'label' => "Administer products",
		]);

		Permission::create([
			'name' => "administer_users",
			'label' => "Administer users",
		]);

		Permission::create([
			'name' => "create_app",
			'label' => "Create an app",
		]);

		Permission::create([
			'name' => "administer_dashboard_products",
			'label' => "Administer products in the dashboard",
		]);

		Permission::create([
			'name' => "view_dashboard_products",
			'label' => "View products in the dashboard",
		]);

		Permission::create([
			'name' => "view_internal_products",
			'label' => "View internal products",
		]);

		Permission::create([
			'name' => "view_admin_backend",
			'label' => "View admin backend",
		]);

		$adminRole = Role::create([
			'name' => "admin",
			'label' => "Admin",
		]);

		$adminRole->allowTo(Permission::all());
		$adminUser->assignRole($adminRole);
		$twoFAUser->assignRole($adminRole);

		$developerRole = Role::create([
			'name' => "developer",
			'label' => "Developer",
		]);

		$developerRole->allowTo('create_app');

		$opcoRole = Role::create([
			'name' => "opco",
			'label' => "Opco",
		]);

		$opcoRole->allowTo(['create_app', 'view_dashboard_products', 'administer_dashboard_products', 'view_admin_backend']);

		$internalRole = Role::create([
			'name' => "internal",
			'label' => "Internal",
		]);

		$internalRole->allowTo(['create_app', 'view_internal_products']);

		$contentCreatorRole = Role::create([
			'name' => "content_creator",
			'label' => "Content creator",
		]);

		$contentCreatorRole->allowTo(['create_app', 'administer_content', 'view_admin_backend']);
	}
}
