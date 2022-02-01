<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(['UserSeeder']);
    }

    /** @test */
    public function can_view_their_profile()
    {
        $this->actingAs(User::first())
            ->get(route('user.profile'))
            ->assertStatus(200);
    }

    /** @test */
    public function can_update_their_profile()
    {
        $this->actingAs(User::first())
            ->put(route('user.profile.update'), [
                'first_name' => 'Wesley',
                'last_name' => 'Martin',
                'email' => 'wes@plusnarrative.com',
                'password' => '&jklfFI9@bI!',
                'password_confirmation' => '&jklfFI9@bI!',
                'locations' => ['za'],
            ])->assertSessionHasNoErrors();
    }

    /** @test */
    public function updating_must_have_a_valid_first_name()
    {
        $this->actingAs(User::first())
            ->put(route('user.profile.update'), [
                'first_name' => '',
                'last_name' => 'Martin',
                'email' => 'wes@plusnarrative.com',
                'password' => '&jklfFI9@bI!',
                'password_confirmation' => '&jklfFI9@bI!',
                'locations' => ['za'],
            ])->assertSessionHasErrors();
    }

    /** @test */
    public function updating_must_have_a_valid_last_name()
    {
        $this->actingAs(User::first())
            ->put(route('user.profile.update'), [
                'first_name' => 'Wesley',
                'last_name' => '',
                'email' => 'wes@plusnarrative.com',
                'password' => '&jklfFI9@bI!',
                'password_confirmation' => '&jklfFI9@bI!',
                'locations' => ['za'],
            ])->assertSessionHasErrors();
    }

    /** @test */
    public function updating_must_have_a_valid_email()
    {
        $this->actingAs(User::first())
            ->put(route('user.profile.update'), [
                'first_name' => 'Wesley',
                'last_name' => 'Martin',
                'email' => 'wesplusnarrative.com',
                'password' => '&jklfFI9@bI!',
                'password_confirmation' => '&jklfFI9@bI!',
                'locations' => ['za'],
            ])->assertSessionHasErrors();
    }

    /** @test */
    public function updating_can_have_an_empty_password()
    {
        $this->actingAs(User::first())
            ->put(route('user.profile.update'), [
                'first_name' => 'Wesley',
                'last_name' => 'Martin',
                'email' => 'wes@plusnarrative.com',
                'password' => '',
                'password_confirmation' => '',
                'locations' => ['za'],
            ])->assertSessionHasNoErrors();
    }

    /** @test */
    public function can_update_a_profile_picture()
    {
        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->actingAs(User::first())
            ->post(route('user.profile.update.picture'), [
                'profile' => $file,
            ])->assertSessionHasNoErrors();
    }

    /** @test */
    public function profile_picture_dimentions_are_less_than_2000()
    {
        $file = UploadedFile::fake()->image('avatar.jpg', 2001, 2001);

        $this->actingAs(User::first())
            ->post(route('user.profile.update.picture'), [
                'profile' => $file,
            ])->assertSessionHasErrors();
    }

    /** @test */
    public function profile_picture_size_is_less_than_5mb()
    {
        $file = UploadedFile::fake()->image('avatar.jpg')->size(5121);

        $this->actingAs(User::first())
            ->post(route('user.profile.update.picture'), [
                'profile' => $file,
            ])->assertSessionHasErrors();
    }
}
