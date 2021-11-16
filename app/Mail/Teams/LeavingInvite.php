<?php

namespace App\Mail\Teams;

use App\User;
use App\Team;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class LeavingInvite
 *
 * @package App\Mail\Teams
 */
class LeavingInvite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var $team
     */
    public $team;

    /**
     * @var $user
     */
    public $dischargedMember;

    /**
     * Create a new message instance.
     *
     * @param Team $team
     * @param User $dischargedMember
     */
    public function __construct(Team $team, User $dischargedMember)
    {
        $this->team = $team;

        $this->dischargedMember = $dischargedMember;
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
            ->to($this->dischargedMember->email, $this->dischargedMember->full_name)
            ->subject("MTN Developer Portal: Update from {$this->team->name} Team")
            ->markdown('emails.teams.invites.member-discharged', [
                'dischargedMember' => $this->dischargedMember,
                'team' => $this->team,
            ]);
    }
}
