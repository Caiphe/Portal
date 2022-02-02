<?php

namespace Tests\Feature;

use App\Content;
use App\Http\Middleware\TwoFA;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPageTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $page;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([TwoFA::class]);
        $this->seed(['UserSeeder', 'ContentSeeder']);

        $this->user = User::whereEmail('wes+2fa@plusnarrative.com')->first();
        $this->page = Content::whereType('page')->first();
    }

    /** @test */
    public function can_not_view_page_admin_as_guest()
    {
        $this->getJson(route('admin.page.index'))->assertUnauthorized();
        $this->getJson(route('admin.page.create'))->assertUnauthorized();
        $this->getJson(route('admin.page.edit', $this->page))->assertUnauthorized();
    }

    /** @test */
    public function can_view_page_index()
    {
        $this->actingAs($this->user)->get(route('admin.page.index'))->assertOk();
    }

    /** @test */
    public function can_view_page_create()
    {
        $this->actingAs($this->user)->get(route('admin.page.create'))->assertOk();
    }

    /** @test */
    public function can_view_page_edit()
    {
        $this->actingAs($this->user)->get(route('admin.page.edit', $this->page))->assertOk();
    }

    /** @test */
    public function can_create_a_page()
    {
        $this->actingAs($this->user)->post(route('admin.page.store'), [
            'title' => 'Is this a test?',
            'body' => 'Why, yes it is',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.page.index'));
    }

    /** @test */
    public function can_update_a_page()
    {
        $this->actingAs($this->user)->put(route('admin.page.update', $this->page), [
            'title' => 'Is this a test?',
            'body' => 'Why, yes it is',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.page.index'));
    }
}
