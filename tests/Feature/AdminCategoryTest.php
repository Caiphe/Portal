<?php

namespace Tests\Feature;

use App\Category;
use App\Http\Middleware\TwoFA;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([TwoFA::class]);
        $this->seed(['UserSeeder', 'CategorySeeder']);

        $this->user = User::whereEmail('wes+2fa@plusnarrative.com')->first();
        $this->category = Category::first();
    }

    /** @test */
    public function can_not_view_category_admin_as_guest()
    {
        $this->getJson(route('admin.category.index'))->assertUnauthorized();
        $this->getJson(route('admin.category.edit', $this->category))->assertUnauthorized();
    }

    /** @test */
    public function can_view_category_index()
    {
        $this->actingAs($this->user)->get(route('admin.category.index'))->assertOk();
    }

    /** @test */
    public function can_view_category_edit()
    {
        $this->actingAs($this->user)->get(route('admin.category.edit', $this->category))->assertOk();
    }

    /** @test */
    public function can_create_a_category()
    {
        $this->actingAs($this->user)->post(route('admin.category.store'), [
            'title' => 'This is a category',
            'heading-title' => 'This is the heading-title',
            'heading-body' => 'This is the heading-body',
            'benefits-body' => 'This is the benefits-body',
            'developer-centric-body' => 'Thiss is the developer-centric-body',
            'bundles-body' => 'This is the bundles-body',
            'products-body' => 'This is the products-body',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.category.index'));
    }

    /** @test */
    public function can_update_a_category()
    {
        $this->actingAs($this->user)->put(route('admin.category.update', $this->category), [
            'title' => 'This is a category',
            'heading-title' => 'This is the heading-title',
            'heading-body' => 'This is the heading-body',
            'benefits-body' => 'This is the benefits-body',
            'developer-centric-body' => 'Thiss is the developer-centric-body',
            'bundles-body' => 'This is the bundles-body',
            'products-body' => 'This is the products-body',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.category.index'));
    }
}
