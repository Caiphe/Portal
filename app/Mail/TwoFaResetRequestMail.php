<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TwoFaResetRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $countries;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $countries)
    {
        $this->user = $user;
        $this->countries = $countries;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('2FA reset request')->markdown('emails.users.twofa-reset-request');
    }
}
