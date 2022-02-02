<?php

namespace Tests\Feature;

use App\Http\Middleware\TwoFA;
use App\Product;
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
        $this->seed(['UserSeeder']);

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
}
