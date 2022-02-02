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
        ]);

        Content::create([
            'title' => 'Understanding OAuth 2.0',
            'slug' => 'understanding-oauth-2-0',
            'type' => 'general_docs',
            'body' => '<h1>Understanding</h1>',
            'published_at' => '2020-04-02 11:31:39',
            'contentable_id' => null,
            'contentable_type' => null,
        ]);
    }
}
