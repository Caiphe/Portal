<?php

namespace App\Mail;

use App\App;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewApp extends Mailable
{
    use Queueable, SerializesModels;

    public $app;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(App $app)
    {
        $this->app = $app->load('developer');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->app->developer->email;

        return $this->withSwiftMessage(function ($message) use ($email) {
            $message->getHeaders()->addTextHeader('Reply-To', $email);
        })
            ->subject('New App')
            ->markdown('emails.new-app');
    }
}
