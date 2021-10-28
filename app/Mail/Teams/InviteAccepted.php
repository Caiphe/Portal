<?php

namespace App\Mail\Teams;

use App\Team;

use App\User;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class InviteAccepted
 *
 * @package App\Mail\Teams
 */
class InviteAccepted extends Mailable
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
     * @var $user
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param Team $team
     * @param User $user
     */
    public function __construct(Team $team, User $user)
    {
        $this->team = $team;

        $this->user = $user;
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
            ->to($this->user->email)
            ->cc($owner->email)
            ->subject("MTN Developer Portal: {$this->user->full_name} has accepted your invite to join {$this->team->name} Team")
            ->markdown('emails.teams.invites.invite-accepted', [
                'team' => $this->team,
                'user' => $this->user,
            ]);
    }
}
