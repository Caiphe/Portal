<?php

namespace App\Mail\Invites;

use App\User;
use App\Team;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class RemoveUser
 *
 * @package App\Mail
 */
class RemoveUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var $team
     */
    public $team;

    /**
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
        $teamOwner = User::find($this->team->owner_id)->first();

        return $this->withSwiftMessage(function ($message) use($teamOwner) {
            $message->getHeaders()->addTextHeader('Reply-To', $teamOwner->email);
        })
            ->to($this->user->email, $this->user->full_name)
            ->subject("MTN Developer Portal: Update from {$this->team->name} Team")
            ->markdown('emails.companies.user-remove', [
                'team' => $this->team,
                'user' => $this->user,
            ]);
    }
}
