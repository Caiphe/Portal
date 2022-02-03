<?php

namespace Tests\Feature;

use App\App;
use App\Services\ApigeeService;
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
    public function can_create_and_edit_an_app()
    {
        $this->be($this->user);

        $this->postJson(route('app.store'), [
            'country' => "za",
            'description' => "Test case description",
            'display_name' => "PlusNarrative App test case",
            'products' => ["helloworld-prodonly"],
            'team_id' => "",
            'url' => ""
        ], [
            'X-Requested-With' => 'XMLHttpRequest'
        ])
            ->assertOk();

        $app = App::where('display_name', 'PlusNarrative App test case')->first();

        $this->putJson(route('app.update', $app), [
            'country' => "za",
            'description' => "Test case description updated",
            'display_name' => "PlusNarrative App test case",
            'products' => ["helloworld-prodonly"],
            'team_id' => "",
            'url' => ""
        ], [
            'X-Requested-With' => 'XMLHttpRequest'
        ])
            ->assertOk();

        // Cleanup
        $resp = ApigeeService::delete("developers/{$this->user->email}/apps/plusnarrative-app-test-case");
        $this->assertTrue($resp->successful());
    }
}
