<?php

namespace App\Mail\Invites;

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
class InviteExternalUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var $team
     */
    public $team;

    /**
     * @var $email
     */
    public $email;

    /**
     * Create a new message instance.
     *
     * @param Team $team
     * @param $email
     */
    public function __construct(Team $team, $email)
    {
        $this->team = $team;

        $this->email = $email;
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
            ->to($this->email)
            ->subject("MTN Developer Portal: {$teamOwner->full_name} has invited you to join {$this->team->name} Team")
            ->markdown('emails.companies.new-external-user-invite', [
                'inviter' => $teamOwner,
                'team' => $this->team,
            ]);
    }
}
