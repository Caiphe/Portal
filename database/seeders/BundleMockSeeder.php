<?php

namespace Database\Seeders;

use App\Bundle;
use Illuminate\Database\Seeder;

class BundleMockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bundle::create([
            'bid' => 'test',
            'name' => 'test',
            'slug' => 'test',
            'display_name' => 'Test',
            'description' => 'This is a test bundle',
            'category_cid' => 'authentication',
            'banner' => '/images/banner-default.png',
        ]);
    }
}