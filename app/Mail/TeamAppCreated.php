<?php

namespace App\Mail;

use App\App;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class TeamAppCreated
 *
 * @package App\Mail
 */
class TeamAppCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $app;

    /**
     * Create a new message instance.
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app->load(['developer', 'country']);
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
            ->subject('New team app from the MTN Developer Portal')
            ->markdown('emails.new-team-app');
    }
}
