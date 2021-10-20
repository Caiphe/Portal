<?php

namespace App\Mail\Teams;

use App\User;
use App\Team;

use App\Mail\Concerns\InviteTokens;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class InternalInvite
 *
 * @package App\Mail\Teams
 */
class InternalInvite extends Mailable
{
    use Queueable, SerializesModels, InviteTokens;

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
     * Create a new message instance.
     *
     * @param Team $team
     * @param User $invitee
     */
    public function __construct(Team $team, User $invitee)
    {
        $this->team = $team;

        $this->invitee = $invitee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $owner = $this->team->owner;

        $tokens = $this->listTokens($this->invitee, $this->team);

        return $this->withSwiftMessage(function ($message) use($owner) {
            $message->getHeaders()->addTextHeader('Reply-To', $owner->email);
        })
            ->to($this->invitee->email, $this->invitee->full_name)
            ->subject("MTN Developer Portal: Update from {$this->team->name} Team")
            ->markdown('emails.teams.invites.internal-invite', [
                'invitee' => $this->invitee,
                'team' => $this->team,
                'tokens' => $tokens,
            ]);
    }
}
