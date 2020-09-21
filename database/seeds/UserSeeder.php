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

		$adminUser1 = User::create([
			'first_name' => 'Wesley',
			'last_name' => 'Martin',
			'email' => 'wes@plusnarrative.com',
			'password' => bcrypt('devport'),
			'email_verified_at' => $now,
			'developer_id' => '2a940d6f-22ce-4d58-9844-a5b4bd7fc689',
			'profile_picture' => '/storage/profile/profile-1.svg',
		]);

		$adminUser2 = User::create([
			'first_name' => 'Lliam',
			'last_name' => 'Scholtz',
			'email' => 'lliam@plusnarrative.com',
			'password' => bcrypt('K*fjksjF890#'),
			'email_verified_at' => $now,
			'developer_id' => 'b673c570-4865-4039-b494-ccaad2f17013',
			'profile_picture' => '/storage/profile/profile-2.svg',
		]);

		$adminUser3 = User::create([
			'first_name' => 'Oluseye',
			'last_name' => 'Farodoye',
			'email' => 'Oluseye.Farodoye@mtn.com',
			'password' => bcrypt('&jklfFI9@bI!'),
			'email_verified_at' => $now,
			'developer_id' => '26795666-6fee-443b-bd23-b5bbb07c7669',
			'profile_picture' => '/storage/profile/profile-3.svg',
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

		$adminRole = Role::create([
			'name' => "admin",
			'label' => "Admin",
		]);

		$adminRole->allowTo(Permission::all());
		$adminUser1->assignRole($adminRole);
		$adminUser2->assignRole($adminRole);
		$adminUser3->assignRole($adminRole);

		$developerRole = Role::create([
			'name' => "developer",
			'label' => "Developer",
		]);

		$developerRole->allowTo('create_app');

		$opcoAdminRole = Role::create([
			'name' => "opco_admin",
			'label' => "Opco Admin",
		]);

		$opcoAdminRole->allowTo(['create_app', 'view_dashboard_products', 'administer_dashboard_products']);

		$opcoRole = Role::create([
			'name' => "opco",
			'label' => "Opco",
		]);

		$opcoRole->allowTo(['create_app', 'view_dashboard_products']);

		$internalRole = Role::create([
			'name' => "internal",
			'label' => "Internal",
		]);

		$internalRole->allowTo(['create_app', 'view_internal_products']);

		$contentCreatorRole = Role::create([
			'name' => "content_creator",
			'label' => "Content creator",
		]);

		$contentCreatorRole->allowTo(['create_app', 'administer_content']);
	}
}
