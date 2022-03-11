<?php

namespace Tests\Feature;

use App\Http\Middleware\TwoFA;
use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;

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
        $this->product = Product::first();
    }

    /** @test */
    public function can_not_view_product_admin_as_guest()
    {
        $this->getJson(route('admin.product.index'))->assertUnauthorized();
        $this->getJson(route('admin.product.edit', $this->product))->assertUnauthorized();
    }

    /** @test */
    public function can_view_product_index()
    {
        $this->actingAs($this->user)->get(route('admin.product.index'))->assertOk();
    }

    /** @test */
    public function can_view_product_edit()
    {
        $this->actingAs($this->user)->get(route('admin.product.edit', $this->product))->assertOk();
    }

    /** @test */
    public function can_update_a_product()
    {
        $this->actingAs($this->user)->put(route('admin.product.update', $this->product), [
            'display_name' => 'Hello World (Production+Only)',
            'category_cid' => 'sms',
            'group' => 'MTN',
            'locations' => ['ug', 'za'],
            'tab' => [
                'title' => [
                    'Overview',
                    'Docs',
                    'test',
                ],
                'body' => [
                    '<p>Overview</p>',
                    '<p>Docs</p>',
                    '<p>Custom+tab</p>',
                ]
            ]
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.product.index'));
    }
}
