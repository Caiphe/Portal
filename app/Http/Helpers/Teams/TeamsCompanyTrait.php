<?php

namespace App\Http\Helpers\Teams;

use App\Notification;
use App\Services\ApigeeService;
use App\Team;
use App\TeamUser;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Mpociot\Teamwork\TeamInvite;
use Mpociot\Teamwork\Facades\Teamwork;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait TeamsCompanyTrait
{
    /**
     * @param $data
     * @return JsonResponse|RedirectResponse|void
     */
    public function storeTeam($data)
    {
        // If it's a string, explode it
        $emails = explode(',', $data->input('emails'));

        //Get first team owner email from Portal and check if it exists in APIGEE
        $owner = User::where('email', $emails[0])->first();
        $user = ApigeeService::get('developers/' . $owner->email);

        //abort action if user does not exist in APIGEE
        if (isset($user['code'])) {
            return redirect()->back()->with('alert', "error:'" . $user['message'] . "'");
        }

        $data['name'] = preg_replace('/[-_±§@#$%^&*()+=!]+/', '', $data['name']);

        $data['logo'] = $this->processLogoFile($data);

        $teamCount = Team::where('owner_id', $owner->id)
            ->where('created_at', '>=', now()->startOfDay())
            ->count();

        if ($teamCount >= 2) {
            return response()->json(['success' => true], 429);
        }

        $team = $this->createTeam($owner, $data->request->all());

        if (!empty($data['emails'])) {
            $teamInviteEmails = $emails;

            $this->sendInvites($teamInviteEmails, $team);
        }

        if ($team) {
            return response()->json(['success' => true], 200);
        }
    }

    /**
     * @param $team
     * @param $data
     * @return JsonResponse|RedirectResponse|void
     */
    public function updateTeam($team, $data)
    {
        $company = Team::findOrFail($team['id']);

        $teamLogo = $team->logo;
        $oldName = $team->name;

        $team_owner = User::where('id', $data['team_owner'])->first();

        if (!$team_owner) {
            return redirect()->back()->with('alert', "error: user does not exist");
        }

        //Check if the team owner exists in APIGEE
        $apigee_user = ApigeeService::get('developers/' . $team_owner['email']);

        //abort action if user does not exist in APIGEE
        if (isset($apigee_user['code'])) {
            return redirect()->back()->with('alert', "error:'" . $apigee_user['message'] . "'");
        }

        $teamAdmin = $team_owner->hasTeamRole($team, 'team_admin');

        if (!$teamAdmin) {
            return response()->json(['success' => true], 424);
        }

        if ($data->has('logo_file')) {
            $teamLogo = $this->processLogoFile($data);
        }

        $data['name'] = preg_replace('/[-_±§@#$%^&*()+=!]+/', '', $data['name']);

        $company->update([
            'name' => $data['name'],
            'url' => $data['url'],
            'contact' => $data['contact'],
            'country' => $data['country'],
            'logo' => $teamLogo,
            'description' => $data['description'],
        ]);

        $companyUpdated = Team::where('id', $company->id)->first();

        //TODO fix team update API
        ApigeeService::updateCompany($companyUpdated, $team_owner);

        foreach ($companyUpdated->users as $user) {
            if ($oldName !== $companyUpdated->name) {
                Notification::create([
                    'user_id' => $user->id,
                    'notification' => "Your team <strong> {$oldName} </strong> has been updated to <strong>{$companyUpdated->name}</strong>, please click <a href='/teams/{$companyUpdated->id}/team'>here</a> to navigate to your team to view the changes",
                ]);
            } else {
                Notification::create([
                    'user_id' => $user->id,
                    'notification' => "Your team <strong>{$companyUpdated->name}</strong> has been updated please click <a href='/teams/{$companyUpdated->id}/team'>here</a> to navigate to your team to view the changes",
                ]);
            }
        }


        return response()->json(['success' => true], 200);
    }

    /**
     * Invite a teammate to a team based on the provided data and team ID.
     *
     * @param mixed $data The data containing the invitee information.
     * @param int $id The ID of the team to which the teammate is invited.
     * @return JsonResponse JSON response indicating the success or failure of the invitation.
     * @throws HttpException If the user is already a member of the team or does not have the required role.
     * @throws NotFoundHttpException If the team or user is not found.
     */
    public function inviteTeammate($data, int $id): JsonResponse
    {
        $invitedEmail = $data['invitee'];
        $team = $this->getTeam($id);

        abort_if(!$team, 404, 'Team was not found');

        $user = User::where('id', $team->owner_id)->first();
        abort_if(!$user->belongsToTeam($team) || !$user->hasTeamRole($team, 'team_admin'), 403, 'Forbidden');

        $invitee = $this->getTeamUserByEmail($invitedEmail);
        abort_if(!$invitee, 404, 'The User was not found');
        abort_if($invitee->belongsToTeam($team), 403, 'User is already a member of this team');

        $isAlreadyInvited = TeamInvite::where('email', $invitedEmail)
            ->where('team_id', $team->id)
            ->exists();

        if ($isAlreadyInvited) {
            return response()->json([
                'success' => true,
                'message' => 'Invite successfully sent to prospective team member of ' . $team->name . '.'
            ], 200);
        }

        if ($team && !$invitee) {
            $invite = $this->createTeamInvite($team, $invitedEmail, 'external', $data['role']);
            if ($invite) {
                $this->sendExternalInvite($team, $invitedEmail);
            }
        } elseif ($team) {
            $invite = Teamwork::hasPendingInvite($invitee->email, $team);
            if (!$invite) {
                Teamwork::inviteToTeam($invitee->email, $team, function ($invite) use ($data, $team, $invitee) {
                    $this->sendInternalInvite($team, $invitee, $invite);
                    $invite->role = $data['role'];
                    $invite->save();
                });
            } elseif ($invite && $invitee->hasTeamInvite($team)) {
                $this->sendRemindingInvite($team, $invitee, $invite);
            }
        }

        Notification::create([
            'user_id' => $invitee->id,
            'notification' => "You have been invited to the team <strong>{$team->name}</strong>. Click <a href='/apps'>here</a> to accept or revoke the invite.",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invite successfully sent to prospective team member of ' . $team->name . '.'
        ], 200);
    }

    /**
     * TODO sync with APIGEE when is fixed from MTN
     * Changes the ownership of the team from the current owner to the provided user.
     *
     * @param array $data
     * @param Team $team
     * @return JsonResponse
     */
    public function changeOwnership(array $data, Team $team): JsonResponse
    {
        //Check if the user is part of the team
        $isTeamMember = TeamUser::where('user_id', $data['new_owner_id'])
            ->where('team_id', $team->id)
            ->exists();

        if (!$isTeamMember) {
            return response()->json([
                'success' => false,
                'message' => 'User is not part of the team'
            ], 404);
        }

        //Get Users ID's to send notification
        $team = $this->getTeam($team->id);
        $userIds = $team->users->pluck('id')->toArray();

        $user = User::where('id', $data['new_owner_id'])
            ->first(['first_name', 'last_name', 'email']);

        //If the user is part of the team then change ownership and send notifications to all team members
        if ($isTeamMember) {
            $owner = User::where('id', $data['new_owner_id'])
                ->where('developer_id', '!=', null)
                ->first();

            $team->update(['owner_id' => $owner->id]);
            $owner->teams()->updateExistingPivot($team, ['role_id' => 7]);

            foreach ($userIds as $id) {
                if ($id !== $owner->id) {
                    Notification::create([
                        'user_id' => $id,
                        'notification' => "<strong>{$owner->full_name}</strong> is now the owner of your team (<strong>{$team->name}</strong>). Please navigate to your <a href='/teams/{$team->id}/team'>team</a> for more info.",
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => $user->full_name . " has been set as this team's new owner."
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => "{$user->full_name} could not be successfully requested to take ownership of {$team->name}"
        ], 400);
    }
}
