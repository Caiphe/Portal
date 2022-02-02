<?php

namespace Tests\Feature;

use App\Content;
use App\Http\Middleware\TwoFA;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDocumentationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $doc;

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
        $this->doc = Content::whereType('general_docs')->first();
    }

    /** @test */
    public function can_not_view_documentation_admin_as_guest()
    {
        $this->getJson(route('admin.doc.index'))->assertUnauthorized();
        $this->getJson(route('admin.doc.create'))->assertUnauthorized();
        $this->getJson(route('admin.doc.edit', $this->doc))->assertUnauthorized();
    }

    /** @test */
    public function can_view_documentation_index()
    {
        $this->actingAs($this->user)->get(route('admin.doc.index'))->assertOk();
    }

    /** @test */
    public function can_view_documentation_create()
    {
        $this->actingAs($this->user)->get(route('admin.doc.create'))->assertOk();
    }

    /** @test */
    public function can_view_documentation_edit()
    {
        $this->actingAs($this->user)->get(route('admin.doc.edit', $this->doc))->assertOk();
    }

    /** @test */
    public function can_create_documentation()
    {
        $this->actingAs($this->user)->post(route('admin.doc.store'), [
            'title' => 'This is a category',
            'heading-title' => 'This is the heading-title',
            'heading-body' => 'This is the heading-body',
            'benefits-body' => 'This is the benefits-body',
            'developer-centric-body' => 'Thiss is the developer-centric-body',
            'bundles-body' => 'This is the bundles-body',
            'products-body' => 'This is the products-body',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.doc.index'));
    }

    /** @test */
    public function can_update_documentation()
    {
        $this->actingAs($this->user)->put(route('admin.doc.update', $this->doc), [
            'title' => 'This is a category',
            'heading-title' => 'This is the heading-title',
            'heading-body' => 'This is the heading-body',
            'benefits-body' => 'This is the benefits-body',
            'developer-centric-body' => 'Thiss is the developer-centric-body',
            'bundles-body' => 'This is the bundles-body',
            'products-body' => 'This is the products-body',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.doc.index'));
    }
}
