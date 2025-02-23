<?php

namespace App\Mail\Teams;

use App\User;
use App\Team;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class OwnershipInvite
 *
 * @package App\Mail\Teams
 */
class OwnershipInvite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Inviting Team
     *
     * @var $team
     */
    public $team;

    /**
     * Invited Team member
     *
     * @var $member
     */
    public $member;

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
            ->subject("MTN Developer Portal: {$owner->full_name} has invited you to join {$this->team->name} Team")
            ->markdown('emails.teams.invites.ownership-invite', [
                'invitee' => $this->invitee,
                'team' => $this->team,
                'tokens' => $tokens,
            ]);
    }
}
