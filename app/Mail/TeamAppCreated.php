<?php

namespace App\Mail;

use App\Team;

use App\User;
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

    public $team;

    /**
     * Create a new message instance.
     *
     * @param Team $team
     */
    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = User::find($this->team->owner_id)->email;

        return $this->withSwiftMessage(function ($message) use ($email) {
            $message->getHeaders()->addTextHeader('Reply-To', $email);
        })
            ->subject('New team app from the MTN Developer Portal')
            ->markdown('emails.new-team-app');
    }
}
