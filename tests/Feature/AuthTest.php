<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function auth_routes_are_visable()
    {
        $this->seed(['CountrySeeder', 'ContentSeeder', 'CategorySeeder']);

        $this->get(route('login'))->assertStatus(200);
        $this->get(route('password.request'))->assertStatus(200);

        // Can not run this at the moment due to sqlite not having the find_in_set mysql function
        // $this->get(route('register'))->assertStatus(200);
    }

    /** @test */
    public function a_user_can_register()
    {
        $this->seed(['CountrySeeder', 'RoleSeeder']);

        $attributes = User::factory()->raw();

        $this->post(route('register'), $attributes)->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', array_diff_key($attributes, ['locations' => 1, 'terms' => 1, 'password' => 1, 'password_confirmation' => 1]));

        $user = User::whereEmail($attributes['email'])->first();

        $this->assertTrue(Hash::check($attributes['password'], $user->password));
        $this->assertFileExists(storage_path(str_replace('/storage', 'app/public', $user->profile_picture)));
    }

    /** @test */
    public function registering_must_have_a_valid_first_name()
    {
        $this->seed(['CountrySeeder', 'RoleSeeder']);

        $attributes = User::factory()->raw(['first_name' => '']);

        $this->post(route('register'), $attributes)->assertSessionHasErrors();
    }

    /** @test */
    public function registering_must_have_a_valid_last_name()
    {
        $this->seed(['CountrySeeder', 'RoleSeeder']);

        $attributes = User::factory()->raw(['last_name' => '']);

        $this->post(route('register'), $attributes)->assertSessionHasErrors();
    }

    /** @test */
    public function registering_must_have_a_valid_email()
    {
        $this->seed(['CountrySeeder', 'RoleSeeder']);

        $attributes = User::factory()->raw(['email' => '']);

        $this->post(route('register'), $attributes)->assertSessionHasErrors();
    }

    /** @test */
    public function user_registering_must_agree_to_terms()
    {
        $this->seed(['CountrySeeder', 'RoleSeeder']);

        $attributes = User::factory()->raw();
        unset($attributes['terms']);

        $this->post(route('register'), $attributes)->assertSessionHasErrors();
    }

    /** @test */
    public function user_can_log_in()
    {
        $this->seed('UserSeeder');

        $this->post(route('login'), [
            'email' => 'wes@plusnarrative.com',
            'password' => '&jklfFI9@bI!'
        ])->assertRedirect(route('app.index'));
    }

    /** @test */
    public function two_fa_user_is_shown_2fa_page()
    {
        $this->seed('UserSeeder');

        $response = $this->post(route('login'), [
            'email' => 'wes@plusnarrative.com',
            'password' => '&jklfFI9@bI!'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('app.index'));

        $this->followRedirects($response)->assertSee('Add authenticator code');
    }

    /** @test */
    public function user_login_is_valid()
    {
        $this->seed('UserSeeder');

        $this->post(route('login'), [
            'email' => 'wess@plusnarrative.com',
            'password' => '&jklfFI9@bI!'
        ])->assertSessionHasErrors();

        $this->post(route('login'), [
            'email' => '',
            'password' => '&jklfFI9@bI!'
        ])->assertSessionHasErrors();

        $this->post(route('login'), [
            'email' => 'wes@plusnarrative.com',
            'password' => ''
        ])->assertSessionHasErrors();
    }
}
