<?php

namespace App\Http\Controllers\Teams;

use App\Team;

use App\Http\Controllers\Controller;
use App\Factory\Teams\InviteFactory;
use App\Http\Requests\Teams\InviteRequest;
use App\Specification\Teams\JoiningTeamInvite;

use Illuminate\Http\JsonResponse;
use Mpociot\Teamwork\Events\UserJoinedTeam;
use Mpociot\Teamwork\Events\UserInvitedToTeam;

/**
 * Class InvitesController
 *
 * @package App\Http\Controllers\Teams
 */
class InvitesController extends Controller
{
    /**
     * User is invited to join a team
     *
     * @param InviteRequest $inviteRequest
     * @return JsonResponse
     */
    public function team(InviteRequest $inviteRequest)
    {
        $validated = $inviteRequest->validated();

        $user = auth()->user();
        $invitees = $validated['invitees'];
        $team = Team::find($validated['team_id']);

        $inviteSent = false;

        $joiningTeamInvite = new JoiningTeamInvite();
        if ($joiningTeamInvite->matches($user, $invitees)) {
            $invitee = $joiningTeamInvite->asQuery($user->newQuery(), $invitees)
                ->get()[0];

            if ($invitee->hasTeamInvite($team)) {
                $invite = InviteFactory::createFromInput([
                    'email' => $invitee->email,
                    'team_id' => $team->id,
                ]);

                $inviteSent = $invite->exists;

                if ($inviteSent) {
                    event(new UserInvitedToTeam($invite));
                }
            }
        }

        if ($inviteSent) {
            return response()->json([
                'success' => $inviteSent,
                'message' => 'Successfully sent invite to selected user.'
            ]);
        } else {
            return response()->json([
                'success' => $inviteSent,
                'message' => sprintf('Could not process Team invitation. Please try again!')
            ]);
        }
    }

    /**
     * User invite to join team is resent
     *
     * @param InviteRequest $inviteRequest
     * @return JsonResponse
     */
    public function resend(InviteRequest $inviteRequest)
    {
        $validated = $inviteRequest->validated();

        $user = auth()->user();
        $invitees = $validated['invitees'];
        $team = Team::find($validated['team_id']);

        $inviteSent = false;

        $joiningTeamInvite = new JoiningTeamInvite();
        if ($joiningTeamInvite->matches($user, $invitees)) {
            $invitee = $joiningTeamInvite->asQuery($user->newQuery(), $invitees)
                ->get()[0];

            if ($invitee->hasTeamInvite($team)) {
                $invite = InviteFactory::createFromInvite([
                    'user_id' => $invitee->id,
                    'email' => $invitee->email,
                    'team_id' => $team->id,
                ]);

                $inviteSent = $invite->exists;

                if ($inviteSent) {
                    event(new UserJoinedTeam($invitee, $validated['team_id']));
                }
            }
        }

        if ($inviteSent) {
            return response()->json([
                'success' => $inviteSent,
                'message' => 'Successfully resent invite to selected user.'
            ]);
        } else {
            return response()->json([
                'success' => $inviteSent,
                'message' => sprintf('Could not process Team invitation resend. Please try again!')
            ]);
        }
    }
}
