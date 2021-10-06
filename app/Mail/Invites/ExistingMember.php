<?php

namespace App\Mail\Invites;

use App\User;
use App\Team;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class ExistingMember
 *
 * @package App\Mail\Invites
 */
class ExistingMember extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Team $team
     */
    public $team;

    /**
     * @var User $user
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
        $teamOwner = User::find($this->team->owner_id)->first();

        return $this->withSwiftMessage(function ($message) use($teamOwner) {
            $message->getHeaders()->addTextHeader('Reply-To', $teamOwner->email);
        })
            ->to($this->invitee->email, $this->invitee->full_name)
            ->subject("MTN Developer Portal: Update from {$this->team->name} Team")
            ->markdown('emails.companies.user-invite', [
                'team' => $this->team,
                'user' => $this->user,
            ]);
    }
}
