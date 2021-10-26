<?php

namespace App\Mail\Teams;

use App\Team;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class ExternalInvite
 *
 * @package App\Mail\Teams
 */
class ExternalInvite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Inviting Team
     *
     * @var $team
     */
    public $team;

    /**
     * Possible User email address
     *
     * @var $email
     */
    public $email;

    /**
     * Create a new message instance.
     *
     * @param Team $team
     * @param string $email
     */
    public function __construct(Team $team, string $email)
    {
        $this->team = $team;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $owner = $this->team->owner;

        return $this->withSwiftMessage(function ($message) use($owner) {
            $message->getHeaders()->addTextHeader('Reply-To', $owner->email);
        })
            ->to($this->email)
            ->subject("MTN Developer Portal: {$owner->full_name} has invited you to join {$this->team->name} Team")
            ->markdown('emails.teams.invites.external-invite');
    }
}
