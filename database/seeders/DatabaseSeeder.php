<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run() {
		$this->call(UserSeeder::class);
		$this->call(CountrySeeder::class);
		$this->call(FaqSeeder::class);
		$this->call(ProductSeeder::class);
		$this->call(AppSeeder::class);
		$this->call(BundleSeeder::class);
		$this->call(RoleSeeder::class);
	}
}
