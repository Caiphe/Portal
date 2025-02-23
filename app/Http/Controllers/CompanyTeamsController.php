<?php

namespace App\Http\Controllers;

use App\App;
use App\Role;
use App\Team;
use App\User;
use App\Country;
use App\TeamUser;
use App\Notification;
use Illuminate\Http\Request;
use App\Product;
use App\Services\ApigeeService;
use Mpociot\Teamwork\TeamInvite;
use App\Concerns\Teams\InviteActions;
use App\Concerns\Teams\InviteRequests;
use Mpociot\Teamwork\Facades\Teamwork;
use App\Http\Requests\Teams\UpdateRequest;
use App\Http\Requests\Teams\RoleUpdateRequest;
use App\Http\Requests\Teams\Invites\InviteRequest;
use App\Http\Requests\Teams\Invites\LeavingRequest;
use App\Http\Requests\Teams\MakeOwnerRequest;
use App\Http\Requests\Teams\Request as TeamRequest;

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

        if ($teams->isEmpty()) {
            return redirect()->route('teams.create');
        }

        $teamInvite = $this->getInviteByEmail($user->email);

        $team = null;
        if ($teamInvite) {
            $team = Team::find($teamInvite->team_id);
        }

        foreach($teams as $team){
            if($team->teamCountry === null){
                abort('403');
            }
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
    public function leaveMakeOwner(MakeOwnerRequest $teamRequest , Team $team)
    {
        $data = $teamRequest->validated();
        $user = $this->getTeamUser(auth()->user()->id);
        $team = $this->getTeam($data['team_id']);
        $newOwner = $this->getTeamUserByEmail($data['user_email']);
        $userIds =  $team->users->pluck('id')->toArray();

        abort_if($team->owner_id !== $user->id, 401, "You are not this team's owner");
        abort_if(!$user, 401, 'Your user could not be found');
        abort_if(!$team, 404, 'The team could not be found');
        abort_if(!$newOwner, 404, 'This user is not known');

        $appCreated = App::where('developer_id', $user->developer_id)->where('team_id', $team->id)->get();

        if($appCreated->count() >= 1){
            foreach($appCreated as $app){
                $app->update([
                    'developer_id' => $newOwner->developer_id
                ]);
            }
        }

        $newOwner->teams()->updateExistingPivot($team, ['role_id' => 7]);
        ApigeeService::removeDeveloperFromCompany($team, $user);
        ApigeeService::updateCompany($team, $newOwner);
        $team->update(['owner_id' => $newOwner->id]);

        Notification::create([
            'user_id' => $newOwner->id,
            'notification' => "The ownership of the team <strong>{$team->name}</strong> has been transfered to you. Click <a href='/teams/{$team->id}/team'>here</a> to navigate to your team.",
        ]);

        $team->users()->detach($user);

        $userIds =  $team->users->pluck('id')->toArray();

        foreach($userIds as $id){
            if($id !== auth()->user()->id){
                Notification::create([
                    'user_id' => $id,
                    'notification' => "A user with the name <strong>{$user->full_name}</strong> has left your team <strong>{$team->name}</strong>. Click <a href='/teams/{$team->id}/team'>here</a> to navigate to your team.",
                ]);
            }
        }

        return response()->json(['success' => true, 'code' => 200], 200);
    }

    public function leave(LeavingRequest $teamRequest, Team $team)
    {
        $usersCount = $team->users->count();
        $data = $teamRequest->validated();
        $team = $this->getTeam($data['team_id']);
        $user = $this->getTeamUser(auth()->user()->id);

        abort_if(!$team, 424, 'The team could not be found');
        abort_if(!$user, 424, 'Your user could not be found');

        if($team->owner_id === auth()->user()->id && $usersCount === 1){
            $teamsInvites = TeamInvite::where('team_id', $team->id)->get();
            if($teamsInvites){
                foreach($teamsInvites as $invite){
                    $invite->delete();
                }
            }

            $appNamesToDelete = App::where('team_id', $team->id)->pluck('name')->toArray();
            if($appNamesToDelete) {
                foreach($appNamesToDelete as $appName){
                    $deletedApps = ApigeeService::delete("companies/{$team->username}/apps/{$appName}");

                    if($deletedApps->successful()){
                        App::where('name', $appName)->delete();
                    }
                }
            }

            Notification::create([
                'user_id' => $user->id,
                'notification' => "You have successfully left your team <strong>{$team->name}</strong>.",
            ]);

            ApigeeService::removeDeveloperFromCompany($team, $user);
            ApigeeService::deleteCompany($team);
            $team->delete();

            return response()->json(['success' => true, 'code' => 200], 200);

        }else{
            $appCreated = App::where('developer_id', $user->developer_id)->where('team_id', $team->id)->get();
            $teamOwnerDeveloperId = User::where('id', $team->owner_id)->pluck('developer_id')->toArray();

            Notification::create([
                'user_id' => $user->id,
                'notification' => "You have successfully left your team <strong>{$team->name}</strong>.",
            ]);

            if($appCreated->count() >= 1){
                foreach($appCreated as $app){
                    $app->update([
                        'developer_id' => $teamOwnerDeveloperId[0]
                    ]);
                }
            }

            ApigeeService::removeDeveloperFromCompany($team, $user);
            $team->users()->detach($user);

            $userIds =  $team->users->pluck('id')->toArray();

            foreach($userIds as $id){
                if($id !== $user->id){
                    Notification::create([
                        'user_id' => $id,
                        'notification' => "A user with the name <strong>{$user->full_name}</strong> has left your team <strong>{$team->name}</strong>. Click <a href='/teams/{$team->id}/team'>here</a> to navigate to your team.",
                    ]);
                }
            }

            return response()->json(['success' => true, 'code' => 200], 200);
        }
    }

    public function remove(LeavingRequest $teamRequest, Team $team){

        $data = $teamRequest->validated();
        $team = $this->getTeam($data['team_id']);
        abort_if(!$team, 424, 'The team could not be found');

        $loggedInUser = auth()->user();
        $user = $this->getTeamUser($data['user_id']);

        if (!$loggedInUser->hasRole('admin') || !$loggedInUser->hasRole('opco')){
            // check requesting user is a team admin for this team
            $teamAdmin = $loggedInUser->hasTeamRole($team, 'team_admin');
            abort_if(!$teamAdmin, 424, "You are not this team's admin");
            abort_if(!$user, 424, 'Your user could not be found');
            abort_if($user->isOwnerOfTeam($team), 424, "You can not remove team's owner");
        }

        if ($team->hasUser($user) && $this->memberLeavesTeam($team, $user)) {
            ApigeeService::removeDeveloperFromCompany($team, $user);

            Notification::create([
                'user_id' => $user->id,
                'notification' => "You have been removed from the team <strong>{$team->name}</strong>. "
            ]);

            return response()->json([
                'success' => true,
                'success:message' => $user->full_name . ' has been successfully removed from ' . $team->name
            ]);
        }

        return response()->json([
            'success' => false,
            'error:message' => $user->full_name . ' could not be removed from ' . $team->name
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

        $locations = Product::isPublic()
            ->WhereNotNull('locations')
            ->Where('locations', '!=', 'all')
            ->select('locations')
            ->get()
            ->implode('locations', ',');

        $locations = array_unique(explode(',', $locations));
        $countries = Country::whereIn('code', $locations)->orderBy('name')->pluck('name', 'code');

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
    public function ownership(InviteRequest $inviteRequest, Team $team)
    {
        $data = $inviteRequest->validated();
        $user = $this->getTeamUserByEmail($data['invitee']);
        abort_if(!$user, 404, 'User was not found');
        $isAlreadyInvited = TeamInvite::where('email', $user->email)->where('team_id', $team->id)->exists();

        if ($isAlreadyInvited) {
            return response()->json([
                'success' => true,
                'success:message' => $user->full_name . ' has been successfully requested to take ownership of ' . $team->name
            ]);
        }

        $invite = $this->createTeamInvite($team, $user->email, 'ownership');
        if ($invite) {

            Notification::create([
                'user_id' => $user->id,
                'notification' => "You have been requested to become owner of the team <strong>{$team->name}</strong> . Click <a href='/teams/{$team->id}/team'>here</a> to accept or revoke the request.",
            ]);

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
        $inviteSent = false;
        $user = $inviteRequest->user();

        $team = $this->getTeam($data['team_id']);
        abort_if(!$team, 404, 'Team was not found');

        // Check if a user is not part of the team or a user does not have admin role of the team
        abort_if(!$user->belongsToTeam($team) || !$user->hasTeamRole($team, 'team_admin'), 403, 'Forbidden');

        $user = $this->getTeamUserByEmail($invitedEmail);
        abort_if(!$user, 404, 'User was not found');

        // Prevent users from receiving invites from a team they are already part of.
        abort_if($user->belongsToTeam($team), 403, 'user is already member of this team');

        $isAlreadyInvited = false;
        if ($user) {
            $isAlreadyInvited = TeamInvite::where('email', $user->email)->where('team_id', $team->id)->exists();
        }

        if ($isAlreadyInvited) {
            return response()->json([
                'success' => true,
                'success:message' => 'Invite successfully sent to prospective team member of ' . $team->name . '.'
            ]);
        }

        if ($team && !$user) {
            $invite = $this->createTeamInvite($team, $invitedEmail, 'external', $data['role']);
            if ($invite) {
                $this->sendExternalInvite($team, $invitedEmail);

                $inviteSent = $invite->exists;
            }
        } elseif ($team) {
            $invite = Teamwork::hasPendingInvite($team, $user->email);

            if (!$invite) {
                Teamwork::inviteToTeam($user->email, $team, function ($invite) use ($data, $team, $user, &$inviteSent) {
                    $this->sendInternalInvite($team, $user, $invite);

                    $invite->role = $data['role'];
                    $invite->save();

                    $inviteSent = $invite->exists;
                });
            } elseif ($invite && $user->hasTeamInvite($team)) {
                $this->sendRemindingInvite($team, $user, $invite);
                $inviteSent = true;
            }
        }

        if ($inviteSent) {
            Notification::create([
                'user_id' => $user->id,
                'notification' => "You have been invited to the team <strong>{$team->name}</strong> . Click <a href='/apps'>here</a> to accept or revoke the invite.",
            ]);

            return response()->json([
                'success' => true,
                'success:message' => 'Invite successfully sent to prospective team member of ' . $team->name . '.'
            ]);
        }

        return response()->json([
            'success' => false,
            'error:message' => $invitedEmail . ' could not be successfully send ' . $team->name . ' prospective member invite.'
        ]);
    }

    /**
     * Show a given Team with its details
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $team = $user->teams()->where('id', $id)->first();
        abort_if(!$team, 404, 'Team was not found');

        $isOwner = $user->isOwnerOfTeam($team);
        $order = [
            'asc' => 'ASC',
            'desc' => 'DESC'
        ][$request->get('order', 'ASC')] ?? 'ASC';

        $team->load(['users' => function ($q) use ($request, $order, $team) {

            $q->when($request->has('sort'), function ($q)  use ($request, $order, $team) {
                switch ($request->get('sort')) {
                    case 'name':
                        $q->orderBy('first_name', $order);
                        break;

                    case 'role':
                        $q->orderBy(TeamUser::select('role_id')
                            ->whereColumn('team_user.user_id', 'users.id')
                            ->where('team_id', $team->id)
                            ->latest()
                            ->take(1), $order);
                        break;

                    case '2fa':
                        if ($order === 'DESC') {
                            $q->orderByRaw('ISNULL(2fa), 2fa ASC');
                        }
                        break;
                }
            });
        }]);

        $order = ['ASC' => 'desc', 'DESC' => 'asc'][$order] ?? 'desc';

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
            'team' => $team,
            'user' => $user,
            'revokedApps' => $apps['revoked'] ?? [],
            'approvedApps' => $apps['approved'] ?? [],
            'userTeamInvite' => $user->getTeamInvite($team),
            'country' => Country::where('code', $team->country)->first(),
            'userTeamOwnershipInvite' => $user->getTeamInvite($team, 'ownership'),
            'userTeamOwnershipRequest' => $user->getTeamOwernerRequest($team) ?: false,
            'isOwner' => $isOwner,
            'isAdmin' => $user->hasTeamRole($team, 'team_admin'),
            'order' => $order,
        ]);
    }

    /**
     * Pesrsist the Team
     */
    public function store(TeamRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $data['name'] = preg_replace('/[±§@#$%^&*()+=!]+/', '', $data['name']);
        $data['logo'] = $this->processLogoFile($request);

        $teamCount = Team::where('owner_id', $user->id)
            ->where('created_at', '>=', now()->startOfDay())
            ->count();

        if($teamCount >= 2){
            return response()->json(['success' => true], 429);
        }

        $team = $this->createTeam($user, $data);

        if (!empty($data['team_members'])) {
            $teamInviteEmails = explode(',', $data['team_members']);
            $this->sendInvites($teamInviteEmails, $team);
        }

        if($team){
            return response()->json(['success' => true], 200);
        }
    }

    /**
     * Edit Team
     */
    public function edit(Request $request, $id)
    {
        $user = $request->user();

        $user->load(['responsibleCountries']);

        $locations = Product::isPublic()
            ->WhereNotNull('locations')
            ->Where('locations', '!=', 'all')
            ->select('locations')
            ->get()
            ->implode('locations', ',');

        $locations = array_unique(explode(',', $locations));
        $countries = Country::whereIn('code', $locations)->orderBy('name')->pluck('name', 'code');

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
        $data = $request->validated();
        $team = Team::findOrFail($id);
        $teamLogo = $team->logo;
        $oldName = $team->name;

        $teamAdmin = auth()->user()->hasTeamRole($team, 'team_admin');

        if(!$teamAdmin){
            return response()->json(['success' => true], 424);
        }

        if($request->has('logo_file')){
            $teamLogo = $this->processLogoFile($request);
        }

        $team->update([
            'url' => $data['url'],
            'contact' => $data['contact'],
            'country' => $data['country'],
            'logo' => $teamLogo,
            'description' => $data['description'],
        ]);

        $updatedFields = array_keys($team->getChanges());
        unset($updatedFields['updated_at']);

        if(empty($updatedFields)){
            return response()->json(['success' => true], 304);
        }

        ApigeeService::updateCompany($team);

        foreach($team->users as $user){
            if($oldName !== $team->name){
                Notification::create([
                    'user_id' => $user->id,
                    'notification' => "Your team <strong> {$oldName} </strong> has been updated to <strong>{$team->name}</strong>, please click <a href='/teams/{$team->id}/team'>here</a> to navigate to your team to view the changes",
                ]);
            }else{
                Notification::create([
                    'user_id' => $user->id,
                    'notification' => "Your team <strong>{$team->name}</strong> has been updated please click <a href='/teams/{$team->id}/team'>here</a> to navigate to your team to view the changes",
                ]);
            }
        }

        return response()->json(['success' => true], 200);
    }

    /**
     * Accept an invite
     */
    public function accept(Request $request)
    {
        $invite = Teamwork::getInviteFromAcceptToken($request->get('token'));
        abort_if(!$invite, 404, 'Invite was not found');

        abort_if($invite->email !== auth()->user()->email, 401, 'You have not been invited to this team');

        $inviteType = ucfirst($invite->type);
        $team = $this->getTeam($invite->team_id);
        $userIds = $team->users->pluck('id')->toArray();
        $user = auth()->user();

        if ($invite->type === 'ownership') {
            $owner = $this->getTeamUserByEmail($invite->email);

            $team->update(['owner_id' => $owner->id]);
            $owner->teams()->updateExistingPivot($team, ['role_id' => 7]);

            foreach($userIds as $id){
                if($id !== $user->id){
                    Notification::create([
                        'user_id' => $id,
                        'notification' => "<strong>{$user->full_name}</strong> is now the owner of your team (<strong>{$team->name}</strong>). Please navigate to your <a href='/teams/{$team->id}/team'>team</a> for more info.",
                    ]);
                }
            }

            ApigeeService::updateCompany($team, $owner);
            Teamwork::acceptInvite($invite);
        } else {
            $resp = ApigeeService::addDeveloperToCompany($team, $invite->user, $invite->role);

            if ($resp->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => $resp['message'] ?? 'There was a problem syncing the team'
                ], $resp->status());
            }

            Teamwork::acceptInvite($invite);
            $roleId = Role::where('name', $invite->role)->first()->id ?? 8;
            $team->users()->updateExistingPivot($invite->user, ['role_id' => $roleId]);

            foreach($userIds as $id){
                if($id !== $user->id){
                    Notification::create([
                        'user_id' => $id,
                        'notification' => "<strong>{$user->full_name}</strong> is now a new member of your team (<strong>{$team->name}</strong>). Please navigate to your <a href='/teams/{$team->id}/team'>team</a> for more info.",
                    ]);
                }
            }
        }

        if (!$invite->exists) {
            return redirect()->route('teams.listing')->with('alert', 'success:' . $inviteType . ' was successfully accepted.');
        }
    }

    /**
     * Reject an invite
     */
    public function reject(Request $request)
    {
        $invite = Teamwork::getInviteFromDenyToken($request->get('token'));
        abort_if(!$invite, 404, 'Invite was not found');

        abort_if($invite->email !== auth()->user()->email, 401, 'You have not been invited to this team');

        $inviteType = ucfirst($invite->type);

        if ($invite) {
            Teamwork::denyInvite($invite);
        }

        if (!$invite->exists) {
            return redirect()->route('teams.listing')->with('alert', 'success:' . $inviteType . ' successfully rejected.');
        }
    }

    /**
     * Make Team member Admin/User
     */
    public function roleUpdate(RoleUpdateRequest $roleRequest, $teamId)
    {
        $data = $roleRequest->validated();
        $user = User::find($data['user_id']);
        $team = Team::find($teamId);
        $role = Role::where('name', $data['role'])->first();

        $updated = false;
        if ($team->hasUser($user)) {
            $updated = $user->teams()->updateExistingPivot($team, ['role_id' => $role->id]);

            Notification::create([
                'user_id' => $user->id,
                'notification' => "Your role in the team <strong>{$team->name}</strong> has been updated to <strong>{$role->label}</strong>.<br/> Please navigate to your <a href='/teams/{$team->id}/team'>team</a> for more info.",
            ]);
        }

        return response()->json(['success' => $updated]);
    }

    /**
     * Delete Team
     */
    public function delete(Team $team)
    {
        $user = auth()->user();
        abort_if($team->owner_id !== $user->id, 401, "You are not this team's owner");

        $teamMembers = $team->users->pluck('id')->toArray();
        $currentUsers = $team->users;

        $teamsInvites = TeamInvite::where('team_id', $team->id)->get();

        if($teamsInvites){
            foreach($teamsInvites as $invite){
                $invite->delete();
            }
        }

        if($currentUsers){
            $userIds = $currentUsers->pluck('id')->toArray();

            foreach($currentUsers as $teamUser){
                ApigeeService::removeDeveloperFromCompany($team, $teamUser);
            }

            foreach($userIds as $id){
                Notification::create([
                    'user_id' => $id,
                    'notification' => "Your team <strong>{$team->name}</strong> has been deleted.",
                ]);
            }

            $team->users()->detach($teamMembers);
        }

        $appNamesToDelete = App::where('team_id', $team->id)->pluck('name')->toArray();

        if($appNamesToDelete) {

            foreach($appNamesToDelete as $appName){
                $deletedApps = ApigeeService::delete("companies/{$team->username}/apps/{$appName}");

                if($deletedApps->successful()){
                    App::where('name', $appName)->delete();
                }
            }
		}

        ApigeeService::deleteCompany($team);
        $team->delete();

        return response()->json(['success' => true, 'code' => 200], 200);
    }
}
