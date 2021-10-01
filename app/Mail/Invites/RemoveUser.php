<?php

namespace App\Mail\CompanyTeams;

use App\User;
use App\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class CompanyRemoveUser
 *
 * @package App\Mail
 */
class CompanyRemoveUser extends Mailable
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
        return $this->subject("Update from {$this->team->name} Team")->markdown('emails.companies.user-remove');
    }
}
