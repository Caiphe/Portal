<?php

namespace Tests\Feature;

use App\Faq;
use App\Http\Middleware\TwoFA;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFaqTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $faq;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([TwoFA::class]);
        $this->seed(['UserSeeder', 'FaqSeeder']);

        $this->user = User::whereEmail('wes+2fa@plusnarrative.com')->first();
        $this->faq = Faq::first();
    }

    /** @test */
    public function can_not_view_faq_admin_pages_as_guest()
    {
        $this->getJson(route('admin.faq.index'))->assertUnauthorized();
        $this->getJson(route('admin.faq.create'))->assertUnauthorized();
        $this->getJson(route('admin.faq.edit', $this->faq))->assertUnauthorized();
    }

    /** @test */
    public function can_view_faq_index()
    {
        $this->actingAs($this->user)->get(route('admin.faq.index'))->assertOk();
    }

    /** @test */
    public function can_view_faq_create()
    {
        $this->actingAs($this->user)->get(route('admin.faq.create'))->assertOk();
    }

    /** @test */
    public function can_view_faq_edit()
    {
        $this->actingAs($this->user)->get(route('admin.faq.edit', $this->faq))->assertOk();
    }

    /** @test */
    public function can_create_an_faq()
    {
        $this->actingAs($this->user)->post(route('admin.faq.store'), [
            'question' => 'Is this a test?',
            'answer' => 'Why, yes it is',
            'category_cid' => 'authentication',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.faq.index'));
    }

    /** @test */
    public function can_update_an_faq()
    {
        $this->actingAs($this->user)->put(route('admin.faq.update', $this->faq), [
            'question' => 'Is this a test?',
            'answer' => 'Why, yes it is',
            'category_cid' => 'authentication',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.faq.index'));
    }
}
