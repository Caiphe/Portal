<?php

namespace Tests\Feature;

use App\Bundle;
use App\Http\Middleware\TwoFA;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBundleTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $bundle;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([TwoFA::class]);
        $this->seed(['UserSeeder', 'CategorySeeder', 'BundleMockSeeder']);

        $this->user = User::whereEmail('wes+2fa@plusnarrative.com')->first();
        $this->bundle = Bundle::first();
    }

    /** @test */
    public function can_not_view_bundle_admin_as_guest()
    {
        $this->getJson(route('admin.bundle.index'))->assertUnauthorized();
        $this->getJson(route('admin.bundle.edit', $this->bundle))->assertUnauthorized();
    }

    /** @test */
    public function can_view_bundle_index()
    {
        $this->actingAs($this->user)->get(route('admin.bundle.index'))->assertOk();
    }

    /** @test */
    public function can_view_bundle_edit()
    {
        $this->actingAs($this->user)->get(route('admin.bundle.edit', $this->bundle))->assertOk();
    }

    /** @test */
    public function can_update_a_bundle()
    {
        $this->actingAs($this->user)->put(route('admin.bundle.update', $this->bundle), [
            'body' => 'This is some body content',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.bundle.index'));
    }
}
