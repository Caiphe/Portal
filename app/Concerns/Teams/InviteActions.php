<?php

namespace App\Concerns\Teams;

use App\Team;
use App\User;

use App\Mail\Teams\InternalInvite;
use App\Mail\Teams\OwnershipInvite;
use App\Mail\Teams\PendingInvite;
use App\Mail\Teams\LeavingInvite;
use App\Mail\Teams\ExternalInvite;
use App\Services\ApigeeService;
use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\TeamInvite;
use Mpociot\Teamwork\Facades\Teamwork;
use Illuminate\Support\Str;

/**
 * Trait InviteActions
 *
 * @package App\Concerns\Teams
 */
trait InviteActions
{
    public function updateRole(Team $team, User $user, $invite)
    {
        return \DB::table('team_user')->where([
            'team_id' => $team->id,
            'user_id' => $user->id
        ])->update([
            'role' => $invite->role
        ]);
    }

    /**
     * Invite a list of potentials members whether they
     * exists or not within the portal
     *
     * @param array $emails
     */
    public function sendInvites(array $emails, Team $team)
    {
        foreach ($emails as $email) {
            $invitee = $this->getTeamUserByEmail($email);

            if ($invitee) {
                if (!Teamwork::hasPendingInvite($team, $invitee->email)) {
                    $invite = Teamwork::inviteToTeam($invitee->email, $team);

                    if ($invite) {
                        $this->sendInternalInvite($team, $invitee, $invite);
                    }
                } else {
                    $this->sendRemindingInvite($team, $invitee, $invitee->getTeamInvite($team));
                }
            } else {
                $invite = $this->createTeamInvite($team, $email, 'external');

                if ($invite) {
                    $this->sendExternalInvite($team, $email);
                }
            }
        }
    }

    /**
     * @param User $owner
     * @param array $data
     * @return Team
     */
    public function createTeam(User $owner, array $data)
    {
        $now = date('Y-m-d H:i:s');
        $teamOptions = [
            'name' => $data['name'],
            'username' => Str::slug($data['name']),
            'url' => $data['url'],
            'contact' => $data['contact'],
            'country' => $data['country'],
            'description' => $data['description'],
            'logo' => $data['logo'],
            'owner_id' => $owner->getKey()
        ];

        $resp = ApigeeService::createCompany($teamOptions, $owner);

        if ($resp->failed()) {
            return null;
        }

        $team = Team::create($teamOptions);
        $owner->attachTeam($team, ['role_id' => 7, 'created_at' => $now, 'updated_at' => $now]);

        return $team;
    }

    /**
     * @param $request
     * @param array $data
     */
    public function processLogoFile($request): string
    {
        if ($request->has('logo_file')) {

            $fileName =  md5(uniqid()) . '.png';

            $path = $request->file('logo_file')->storeAs("public/team", $fileName);

            return str_replace('public', '/storage', $path);
        }

        return '/storage/profile/profile-' . rand(1, 32) . '.svg';
    }

    /**
     * Prepares simple team's data for persistences
     *
     * @param array $data
     * @return array
     */
    public function prepareData(array $data)
    {
        return [
            'name' => $data['name'],
            'url' => $data['url'],
            'contact' => $data['contact'],
            'description' => $data['description'],
            'country' => $data['country'],
        ];
    }

    /**
     * Get any invite for a given User email address
     *
     * @param string $emailAddress
     */
    public function getInviteByEmail(string $emailAddress)
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

        if ($team->users()->detach($user->id)) {

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
    public function sendInternalInvite($team, $user, $invite)
    {
        Mail::to($team->owner->email)
            ->send(new InternalInvite($team, $user, $invite));
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
