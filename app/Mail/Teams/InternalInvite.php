<?php

namespace App\Mail\Teams;

use App\User;
use App\Team;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

/**
 * Class InternalInvite
 *
 * @package App\Mail\Teams
 */
class InternalInvite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Inviting Team
     *
     * @var Team $team
     */
    public $team;

    /**
     * Invited User
     *
     * @var User $invitee
     */
    public $invitee;

    /**
     * @var $invite
     */
    public $invite;


    /**
     * Create a new message instance.
     *
     * @param Team $team
     * @param User $invitee
     */
    public function __construct(Team $team, User $invitee, $invite)
    {
        $this->team = $team;
        $this->invitee = $invitee;
        $this->invite = $invite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $owner = $this->team->owner;
        $tokens = [
            'accept_token' => $this->invite->accept_token,
            'reject_token' => $this->invite->deny_token,
        ];

        return $this->withSwiftMessage(function ($message) use($owner) {
            $message->getHeaders()->addTextHeader('Reply-To', $owner->email);
        })
            ->to($this->invitee->email, $this->invitee->full_name)
            ->subject("MTN Developer Portal: Update from {$this->team->name} Team")
            ->markdown('emails.teams.invites.internal-invite', [
                'tokens' => $tokens,
            ]);
    }
}
