<?php

namespace Database\Seeders;

use App\Content;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Content::create([
            'title' => 'Terms and conditions',
            'slug' => 'terms-and-conditions',
            'type' => 'page',
            'body' => '<h1>Terms</h1>',
            'published_at' => '2020-04-02 11:31:39',
            'contentable_id' => null,
            'contentable_type' => null,
            'created_at' => '2020-04-02 11:31:39',
            'updated_at' => '2020-04-02 11:31:39',
        ]);
    }
}
