<?php

namespace App\Http\Controllers;

use App\App;
use App\Http\Requests\Teams\RoleUpdateRequest;
use App\Team;
use App\Country;

use App\Concerns\Teams\InviteActions;
use App\Concerns\Teams\InviteRequests;

use App\Http\Requests\Teams\UpdateRequest;
use App\Http\Requests\Teams\Invites\InviteRequest;
use App\Http\Requests\Teams\Invites\LeavingRequest;
use App\Http\Requests\Teams\Request as TeamRequest;

use Illuminate\Http\Request;

use Mpociot\Teamwork\Facades\Teamwork;
use Mpociot\Teamwork\Events\UserInvitedToTeam;

/**
 * Class CompanyTeamsController
 *
 * @package App\Http\Controllers
 */
class CompanyTeamsController extends Controller
{
    use InviteRequests, InviteActions;

    /**
     * List available logged in User's teams
     * or allow them to create ones
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $teams = $user->load([
            'teams' => fn ($team) => $team->with('teamCountry')->withCount('users', 'apps')
        ])->teams;

        if (!$teams) {
            return redirect()->route('teams.create');
        }

        $teamInvite = $this->getInviteByEmail($user->email);

        $team = null;
        if ($teamInvite) {
            $team = Team::find($teamInvite->team_id);
        }

        return view('templates.teams.index', [
            'teams' => $teams,
            'user' => $user,
            'teamInvite' => $teamInvite,
            'team' => $team,
        ]);
    }

    /**
     * Leave/Delete endpoint(s)
     */
    public function leave(LeavingRequest $teamRequest, Team $team)
    {
        $data = $teamRequest->validated();

        $team = $this->getTeam($data['team_id']);
        $user = $this->getTeamUser($data['user_id']);

        if ($team->hasUser($user) && $this->memberLeavesTeam($team, $user)) {
            return response()->json([
                'success' => true,
                'success:message' => $user->full_name . ' has successfully left ' . $team->name
            ]);
        }

        return response()->json([
            'success' => false,
            'error:message' => $user->full_name . ' could not leave and/or be removed from ' . $team->name
        ]);
    }

    /**
     * Create Team
     *
     */
    public function create(Request $request)
    {
        $user = $request->user();

        $user->load(['responsibleCountries']);

        $countries = Country::whereIn('code', Country::all()->pluck('code'))->orderBy('name')->pluck('name', 'code');

        $teamInvite = $this->getInviteByEmail($user->email);

        $invitingTeam = null;
        if ($teamInvite) {
            $invitingTeam = $this->getTeam($teamInvite->team_id);
        }

        return view('templates.teams.create', [
            'team' => $invitingTeam,
            'teamInvite' => $teamInvite,
            'countries' => $countries,
            'user' => $user
        ]);
    }

    /**
     * Transfer ownership request endpoint
     */
    public function ownership(InviteRequest $inviteRequest)
    {
        $data = $inviteRequest->validated();

        $team = $this->getTeam($data['team_id']);
        $user = $this->getTeamUserByEmail($data['invitee']);

        $invite = $this->createTeamInvite($team, $user->email, 'ownership');

        if ($invite) {

            $this->sendOwnershipInvite($team, $user, $invite);

            return response()->json([
                'success' => true,
                'success:message' => $user->full_name . ' has been successfully requested to take ownership of ' . $team->name
            ]);
        }

        return response()->json([
            'success' => false,
            'error:message' => $user->full_name . ' could not be successfully requested to take ownership of ' . $team->name
        ]);
    }

    /**
     * Invite endpoint
     */
    public function invite(InviteRequest $inviteRequest)
    {
        $data = $inviteRequest->validated();
        $invitedEmail = $data['invitee'];
        $team = $this->getTeam($data['team_id']);
        $user = $this->getTeamUserByEmail($invitedEmail);
        $inviteSent = false;

        if (!$user && $team) {

            $invite = $this->createTeamInvite($team, $invitedEmail, 'external');

            if ($invite) {
                $this->sendExternalInvite($team, $invitedEmail);

                $inviteSent = $invite->exists;
            }
        } elseif ($user->exists) {
            $invite = Teamwork::hasPendingInvite($team, $user->email);

            if (!$invite) {
                Teamwork::inviteToTeam($user->email, $team, function ($invite) use (&$inviteSent) {
                    event(new UserInvitedToTeam($invite));

                    $inviteSent = $invite->exists;
                });
            } elseif ($invite && $user->hasTeamInvite($team)) {
                $this->sendRemindingInvite($team, $user, $invite);
                $inviteSent = true;
            }
        }

        if ($inviteSent) {

            return response()->json([
                'success' => true,
                'success:message' => 'Invite successfully sent to prospective team member of ' . $team->name . '.'
            ]);
        }

        return response()->json([
            'success' => false,
            'error:message' => $user->full_name . ' could not be successfully send ' . $team->name . ' prospective member invite.'
        ]);
    }

    /**
     * Show a given Team with its details
     */
    public function show(Request $request, $id)
    {
        $team = $request->user()->teams()->where('id', $id)->first();

        $apps = App::with(['products.countries', 'country', 'developer', 'team'])
            ->whereHas('team', function ($q) use ($team) {
                if ($team) {
                    $q->where('id', $team->id);
                }
            })
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('status');

        return view('templates.teams.show', [
            'approvedApps' => $apps['approved'] ?? [],
            'revokedApps' => $apps['revoked'] ?? [],
            'country' => Country::where('code', $team->country)->first(),
            'team' => $team,
        ]);
    }

    /**
     * Pesrsist the Team
     */
    public function store(TeamRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        if (!$this->processLogoFile($request, $data)) {
            abort(422, 'Could not process logo file upload for your team.');
        }

        $team = $this->createTeam($user, $data);

        if (!empty($data['team_members'])) {
            $this->sendInvites($data['team_members'], $team);
        }

        if (strpos(url()->previous(), 'teams/create') !== false) {
            return redirect()->route('teams.listing')->with('alert', "success:{$team->name} has been created");
        }

        return redirect()->back()->with('alert', "success:{$team->name} has been created");
    }

    /**
     * Edit Team
     */
    public function edit(Request $request, $id)
    {
        $user = $request->user();

        $user->load(['responsibleCountries']);

        $countries = Country::whereIn('code', Country::all()->pluck('code'))->orderBy('name')->pluck('name', 'code');

        return view('templates.teams.update', [
            'team' => $this->getTeam($id),
            'countries' => $countries,
        ]);
    }

    /**
     * Persist team update
     */
    public function update(UpdateRequest $request, $id)
    {
        $validated = $request->validated();

        $team = Team::find($id);

        if ($request->post() && $team) {

            $data = $this->prepareData($validated);

            if (!$this->processLogoFile($request, $data)) {
                abort(422, 'Could not process logo file upload for your team.');
            }

            if ($team->update($data)) {
                return redirect()->route('team.show', $team->id)
                    ->with('success: Your team was successfully updated.');
            }
        }
    }

    /**
     * Accept an invite
     */
    public function accept(Request $request)
    {
        $invite = Teamwork::getInviteFromAcceptToken($request->get('token'));
        $inviteType = ucfirst($invite->type);


        if ($invite->type === 'ownership') {

            $team = $this->getTeam($invite->team_id);
            $owner = $this->getTeamUserByEmail($invite->email);

            auth()->user()->teams()->detach($team->id);
            $team->update(['owner_id' => $owner->id]);

            Teamwork::acceptInvite($invite);
        } else {

            $team = $this->getTeam($invite->team_id);

            Teamwork::acceptInvite($invite);
        }

        if (!$invite->exists) {
            return redirect()->route('teams.listing')->with('success: ' . $inviteType . ' was successfully accepted.');
        }
    }

    /**
     * Reject an invite
     */
    public function reject(Request $request)
    {
        $invite = Teamwork::getInviteFromDenyToken($request->get('token'));
        $inviteType = ucfirst($invite->type);

        if ($invite) {
            Teamwork::denyInvite($invite);
        }

        if (!$invite->exists) {
            return redirect()->route('teams.listing')->with('success:' . $inviteType . ' successfully rejected.');
        }
    }

    /**
     * Make Team member Admin/User
     */
    public function roleUpdate(RoleUpdateRequest $roleRequest)
    {
        $data = $roleRequest->validated();

        $team = $this->getTeam($data['team_id']);
        $user = $this->getUser($data['user_id']);

        $updated = false;
        if ($team->hasUser($user)) {
            $updated = $this->updateRole($team, $user, $data['role']);
        }

        return response()->json(['success' => $updated]);
    }
}
