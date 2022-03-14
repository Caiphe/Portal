<?php

namespace Tests\Feature;

use App\Http\Middleware\TwoFA;
use App\Services\ApigeeService;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAppTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([TwoFA::class]);
        $this->seed(['UserSeeder', 'CategorySeeder', 'CountrySeeder', 'ProductSeeder']);

        $this->user = User::whereEmail('wes+2fa@plusnarrative.com')->first();
    }

    /** @test */
    public function can_not_view_app_admin_as_guest()
    {
        $this->getJson(route('admin.dashboard.index'))->assertUnauthorized();
    }

    /** @test */
    public function can_view_app_index()
    {
        $this->actingAs($this->user)->get(route('admin.dashboard.index'))->assertOk();
    }

    /** @test */
    public function admin_can_create_an_app()
    {
        $this->be($this->user);

        $this->postJson(route('app.store'), [
            'country' => "za",
            'description' => "Test case description",
            'display_name' => "Plusnarrative admin app test case",
            'products' => ["helloworld-prodonly"],
            'app_owner' => $this->user->email,
            'url' => ""
        ], [
            'X-Requested-With' => 'XMLHttpRequest'
        ])
            ->assertOk();

        // Cleanup
        $resp = ApigeeService::delete("developers/{$this->user->email}/apps/plusnarrative-admin-app-test-case");
        $this->assertTrue($resp->successful());
    }
}
