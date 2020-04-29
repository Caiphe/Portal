<?php

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
			'password' => bcrypt('devport'),
			'email_verified_at' => $now,
			'developer_id' => '2a940d6f-22ce-4d58-9844-a5b4bd7fc689',
			'profile_picture' => '/storage/profile/profile-1.svg',
		]);

		$developerUser = User::create([
			'first_name' => 'developer',
			'last_name' => 'User',
			'email' => 'developer@user.com',
			'password' => bcrypt('devport'),
			'email_verified_at' => $now,
			'developer_id' => 'thisisnotadeveloperid',
			'profile_picture' => '/storage/profile/profile-2.svg',
		]);

		$opcoAdminUser = User::create([
			'first_name' => 'Opco Admin',
			'last_name' => 'User',
			'email' => 'opco-admin@user.com',
			'password' => bcrypt('devport'),
			'email_verified_at' => $now,
			'developer_id' => 'thisisnotadeveloperid',
			'profile_picture' => '/storage/profile/profile-3.svg',
		]);

		$opcoUser = User::create([
			'first_name' => 'Opco',
			'last_name' => 'User',
			'email' => 'opco@user.com',
			'password' => bcrypt('devport'),
			'email_verified_at' => $now,
			'developer_id' => 'thisisnotadeveloperid',
			'profile_picture' => '/storage/profile/profile-4.svg',
		]);

		$internalUser = User::create([
			'first_name' => 'Internal',
			'last_name' => 'User',
			'email' => 'internal@user.com',
			'password' => bcrypt('devport'),
			'email_verified_at' => $now,
			'developer_id' => 'thisisnotadeveloperid',
			'profile_picture' => '/storage/profile/profile-5.svg',
		]);

		$administerContentPermission = Permission::create([
			'name' => "administer_content",
			'label' => "Administer content",
		]);

		$administerProductsPermission = Permission::create([
			'name' => "administer_products",
			'label' => "Administer products",
		]);

		$administerUsersPermission = Permission::create([
			'name' => "administer_users",
			'label' => "Administer users",
		]);

		$createAppPermission = Permission::create([
			'name' => "create_app",
			'label' => "Create an app",
		]);

		$administerProductsPermission = Permission::create([
			'name' => "administer_dashboard_products",
			'label' => "Administer products in the dashboard",
		]);

		$viewProductsPermission = Permission::create([
			'name' => "view_dashboard_products",
			'label' => "View products in the dashboard",
		]);

		$viewInternalProductsPermission = Permission::create([
			'name' => "view_internal_products",
			'label' => "View internal products",
		]);

		$adminRole = Role::create([
			'name' => "admin",
			'label' => "Admin",
		]);

		$adminRole->allowTo(Permission::all());
		$adminUser->assignRole($adminRole);

		$developerRole = Role::create([
			'name' => "developer",
			'label' => "Developer",
		]);

		$developerRole->allowTo('create_app');
		$developerUser->assignRole($developerRole);

		$opcoAdminRole = Role::create([
			'name' => "opco_admin",
			'label' => "Opco Admin",
		]);

		$opcoAdminRole->allowTo(['create_app', 'view_dashboard_products', 'administer_dashboard_products']);
		$opcoAdminUser->assignRole($opcoAdminRole);

		$opcoRole = Role::create([
			'name' => "opco",
			'label' => "Opco",
		]);

		$opcoRole->allowTo(['create_app', 'view_dashboard_products']);
		$opcoUser->assignRole($opcoRole);

		$internalRole = Role::create([
			'name' => "internal",
			'label' => "Internal",
		]);

		$internalRole->allowTo(['create_app', 'view_internal_products']);
		$internalUser->assignRole($internalRole);
	}
}
