<?php

namespace App\Http\Controllers\Teams;

use App\Factory\Teams\InviteFactory;
use App\Team;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\TeamRequest;
use App\Http\Requests\Teams\InviteRequest;
use App\Specification\Teams\JoiningTeamInvite;
use App\Specification\Teams\TransferOwnershipInvite;

use Illuminate\Http\JsonResponse;
use Mpociot\Teamwork\Events\UserInvitedToTeam;
use Mpociot\Teamwork\Facades\Teamwork;

/**
 * Class OwnershipsControllers
 *
 * @package App\Http\Controllers\Teams
 */
class OwnershipsController extends Controller
{
    /**
     * A Team should already know its Members
     *
     * @param TeamRequest $teamRequest
     * @return JsonResponse
     */
    public function select(TeamRequest $teamRequest)
    {
        $validated = $teamRequest->validated();

        $teamMembers = Team::with('users')
            ->whereHas('users', function($q) use($validated) {
                $q->where('team_id', $validated['team_id'])
                    ->whereNotNull(['verified_at']);
            })
            ->get();

        return response()->json($teamMembers, 200);
    }

    /**
     * Team ownership transfer exchange process
     *
     * @param InviteRequest $inviteRequest
     * @return JsonResponse
     */
    public function transfer(InviteRequest $inviteRequest)
    {
        $validated = $inviteRequest->validated();

        $user = auth()->user();
        $team = Team::findOrFail($validated['team_id']);
        $joiningTeamInvite = new JoiningTeamInvite();

        $inviteSent = false;
        if ($joiningTeamInvite->matches($user, $validated['invitees'])) {
            foreach ($joiningTeamInvite->asQuery($user->newQuery(), $validated['invitees'])->get() as $invitee){
                if( !Teamwork::hasPendingInvite( $invitee->email, $team) ) {
                    Teamwork::inviteToTeam( $invitee->email, $team, function( $invite ) use ($team, $invitee, &$inviteSent) {
                        $invite = InviteFactory::createFromInvite([
                            'user_id' => $invitee->id,
                            'email' => $invitee->email,
                            'team_id' => $team->id,
                        ]);
                        event(new UserInvitedToTeam($invite));
                        $inviteSent = true;
                    });
                }
            }
        }

        if ($inviteSent) {
            return response()->json([
                'success' => $inviteSent,
                'message' => 'User invite(s) has been sent successfully! '
            ]);
        } else {
            return response()->json([
                'success' => $inviteSent,
                'message' => 'Could not process Team invitations. Please contact Administrator!'
            ]);
        }
    }

    /**
     * User accepts team invitation from Team owner
     *
     * @param InviteRequest $inviteRequest
     * @return JsonResponse
     */
    public function accept(InviteRequest $inviteRequest)
    {
        $validated = $inviteRequest->validated();

        $inviteSpec = new TransferOwnershipInvite();
        $invite = Teamwork::getInviteFromAcceptToken($validated['token']);
        $inviteAccepted = false;

        if( $inviteSpec->matches($invite) ) {
            $invitingTeam = Team::find($invite->team_id);
            Teamwork::acceptInvite( $invite );
            $inviteAccepted = auth()->user()->isOwnerOfTeam($invitingTeam);
        }

        if (!$inviteAccepted) {
            return response()->json([
                'success' => $inviteAccepted,
                'message' => 'There was a problem rejecting your ownership invite.'
            ]);
        } else {
            return response()->json([
                'success' => $inviteAccepted,
                'message' => 'Team ownership invite rejected successfully!'
            ]);
        }
    }

    /**
     * User rejects Team ownership invite
     *
     * @param InviteRequest $inviteRequest
     * @return JsonResponse
     */
    public function revoke(InviteRequest $inviteRequest)
    {
        $validated = $inviteRequest->validated();

        $inviteSpec = new TransferOwnershipInvite();
        $invite = Teamwork::getInviteFromAcceptToken( $validated['token']);
        $inviteRejected = false;

        if( $inviteSpec->matches($invite) ) {
            Teamwork::denyInvite( $invite );
            $inviteRejected = $invite->exists;
        }

        if (!$inviteRejected) {
            return response()->json([
                'success' => $inviteRejected,
                'message' => 'There was a problem rejecting your ownership invite.'
            ]);
        } else {
            return response()->json([
                'success' => $inviteRejected,
                'message' => 'Team ownership invite rejected successfully!'
            ]);
        }
    }
}
