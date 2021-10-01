<?php

namespace App\Mail\Invites;

use App\User;
use App\Team;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteExistingUser extends Mailable
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
        return $this->subject("Update from {$this->team->name} Team")->markdown('emails.companies.user-invite');
    }
}
