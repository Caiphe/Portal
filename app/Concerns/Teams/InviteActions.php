<?php

namespace App\Concerns\Teams;

use App\Team;
use App\User;
use App\Country;

use App\Mail\Teams\InternalInvite;
use App\Mail\Teams\OwnershipInvite;
use App\Mail\Teams\PendingInvite;
use App\Mail\Teams\LeavingInvite;
use App\Mail\Teams\ExternalInvite;

use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\TeamInvite;
use Mpociot\Teamwork\Facades\Teamwork;

/**
 * Trait InviteActions
 *
 * @package App\Concerns\Teams
 */
trait InviteActions
{
    /**
     * Invite a list of potentials members whether they
     * exists or not within the portal
     *
     * @param array $emails
     */
    public function sendInvites(array $emails)
    {
        array_map( function(string $email) {

            $invitee = $this->getTeamUserByEmail($email);

            if ( $invitee ) {
                if ( !Teamwork::hasPendingInvite($team, $invitee->email) ) {
                    Teamwork::inviteToTeam( $invitee->email, $team);
                } else {
                    $this->sendRemindingInvite($team, $invitee);
                }
            } else {
                $invite = $this->createTeamInvite( $team, $email, 'external' );

                if ( $invite ) {
                    $this->sendExternalInvite( $team, $email );
                }
            }

        }, $emails);
    }

    /**
     * @param User $owner
     * @param array $data
     * @return Team
     */
    public function createTeam(User $owner, array $data)
    {
        $team = Team::create([
            'name' => $data['name'],
            'url' => $data['url'],
            'contact' => $data['contact'],
            'country' => $data['country'],
            'description' => $data['description'],
            'logo' => $data['logo'],
            'owner_id' => $owner->getKey()
        ]);

        $owner->attachTeam($team);

        return $team;
    }

    /**
     * @param $request
     * @param array $data
     */
    public function processLogoFile( $request, array &$data )
    {
        if ( $request->has('logo_file') ) {

            $fileName =  md5(uniqid()) . '.' . $request->file('logo_file')->extension();

            $path = $request->file('logo_file')->storeAs("public/team/", $fileName);

            $data['logo'] = str_replace('public', '/storage', $path);
        }

        return isset($data['logo']);
    }

    /**
     * Prepares simple team's data for persistences
     *
     * @param array $data
     * @return array
     */
    public function prepareData( array $data )
    {
        $prepared = [
            'name' => $data['name'],
            'url' => $data['url'],
            'contact' => $data['contact'],
            'description' => $data['description'],
        ];

        if (isset($data['country'])) {
            $prepared['country'] = Country::where('code', $data['country'])->value('name');
        }

        return $prepared;
    }

    /**
     * Get any invite for a given User email address
     *
     * @param string $emailAddress
     */
    public function getInviteByEmail( string $emailAddress)
    {
        return TeamInvite::where('email', $emailAddress)->first();
    }

    /**
     * A member leaves a Team
     *
     * @param $team
     * @param $user
     * @return true
     */
    public function memberLeavesTeam($team, $user)
    {
        $userLeft = false;

        if ( $team->users()->detach($user->id) ) {

            Mail::to($user->email)
                ->send(new LeavingInvite($team, $user));

            $userLeft = true;
        }

        return $userLeft;
    }

    /**
     * Invite email sent to external User
     *
     * @param $team
     * @param $email
     */
    public function sendExternalInvite($team, $email)
    {
        Mail::to($team->owner->email)
            ->send(new ExternalInvite($team, $email));
    }

    /**
     * Invite email sent to internal User
     *s
     * @param $team
     * @param $user
     */
    public function sendInternalInvite($team, $user)
    {
        Mail::to($team->owner->email)
            ->send(new InternalInvite($team, $user));
    }

    /**
     * Invite another member to take ownership
     *
     * @param Team $team
     * @param User $user
     */
    public function sendOwnershipInvite(Team $team, User $user, $invite)
    {
        Mail::to($team->owner->email)
            ->send(new OwnershipInvite($team, $user, $invite));
    }

    /**
     * Resend user invite
     *
     * @param Team $team
     * @param User $user
     */
    public function sendRemindingInvite(Team $team, User $user, $invite)
    {
        Mail::to($team->owner->email)
            ->send(new PendingInvite($team, $user, $invite));
    }
}
