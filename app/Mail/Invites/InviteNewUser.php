<?php

namespace App\Mail\CompanyTeams;

use App\User;
use App\Team;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class InviteNewUser
 *
 * @package App\Mail
 */
class InviteNewUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var $team
     */
    public $team;

    /**
     * @var $inviter
     */
    public $inviter;

    /**
     * Create a new message instance.
     *
     * @param Team $team
     * @param User $user
     */
    public function __construct(Team $team, User $user)
    {
        $this->team = $team;

        $this->inviter = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("{$this->inviter->full_name} has invited you to join {$this->team->name}")->markdown('emails.companies.new-user-invite');
    }
}
