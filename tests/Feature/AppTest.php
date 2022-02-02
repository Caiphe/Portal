<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    use RefreshDatabase;


    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(['UserSeeder', 'CategorySeeder', 'CountrySeeder', 'ProductSeeder']);

        $this->user = User::first();
    }

    /** @test */
    public function can_not_view_app_list_as_guest()
    {
        $this->getJson(route('app.index'))->assertUnauthorized();
    }

    /** @test */
    public function can_view_app_list_as_authed_user()
    {
        $this->actingAs($this->user)->getJson(route('app.index'))->assertOk();
    }

    /** @test */
    public function can_create_an_app()
    {
        $this->actingAs($this->user)
            ->postJson(route('app.store'), [
                'country' => "za",
                'description' => "Test case description",
                'display_name' => "App test case",
                'products' => ["helloworld"],
                'team_id' => "",
                'url' => ""
            ],[
                'X-Requested-With' => 'XMLHttpRequest'
            ])
            ->assertOk();
    }
}
