<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_see_contact_page()
    {
        $this->get(route('contact.index'))->assertStatus(200);
    }

    /** @test */
    public function can_submit_the_contact_form()
    {
        $this->post(route('contact.send'), [
            'first_name' => 'Wesley',
            'last_name' => 'Martin',
            'email' => 'wes@plusnarrative.com',
            'message' => 'This is the message',
            'categories' => 'Messaging',
            'username' => ''
        ])->assertSessionDoesntHaveErrors();
    }

    /** @test */
    public function first_name_is_valid()
    {
        $this->post(route('contact.send'), [
            'first_name' => '',
            'last_name' => 'Martin',
            'email' => 'wes@plusnarrative.com',
            'message' => 'This is the message',
            'categories' => 'Messaging',
            'username' => ''
        ])->assertSessionHasErrors();
    }

    /** @test */
    public function last_name_is_valid()
    {
        $this->post(route('contact.send'), [
            'first_name' => 'Wesley',
            'last_name' => '',
            'email' => 'wes@plusnarrative.com',
            'message' => 'This is the message',
            'categories' => 'Messaging',
            'username' => ''
        ])->assertSessionHasErrors();
    }

    /** @test */
    public function email_is_valid()
    {
        $this->post(route('contact.send'), [
            'first_name' => 'Wesley',
            'last_name' => 'Martin',
            'email' => '',
            'message' => 'This is the message',
            'categories' => 'Messaging',
            'username' => ''
        ])->assertSessionHasErrors();
    }
}
