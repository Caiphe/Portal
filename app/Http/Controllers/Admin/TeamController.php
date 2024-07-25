<?php

namespace App\Http\Controllers\Admin;

use App\App;
use App\Http\Requests\TeamOwnerRequest;
use App\Http\Requests\Teams\Invites\InviteRequest;
use App\Role;
use App\Team;
use App\User;
use App\Country;
use App\Notification;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Traits\CountryTraits;
use App\Services\ApigeeService;
use Mpociot\Teamwork\TeamInvite;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Concerns\Teams\InviteActions;
use Illuminate\Http\RedirectResponse;
use App\Concerns\Teams\InviteRequests;
use App\Http\Requests\Admin\TeamRequest;
use App\Http\Helpers\Teams\TeamsCompanyTrait;
use App\Http\Requests\Teams\RoleUpdateRequest;
use App\Http\Requests\Teams\Invites\LeavingRequest;

class TeamController extends Controller
{
    use  InviteRequests, InviteActions, TeamsCompanyTrait, CountryTraits;

    /**
     * Retrieves a paginated list of teams based on the provided request parameters.
     *
     * @param Request $request The HTTP request object containing query parameters.
     * @return View The view displaying the list of teams.
     */
    public function index(Request $request): View
    {
        $country = $request->get('country', "");
        $numberPerPage = (int)$request->get('number_per_page', '15');
        $currentUser = $request->user();

        $teams = Team::with(['apps', 'users'])
            ->when(!$currentUser->hasRole('admin') && $currentUser->hasRole('opco'), function ($query) use ($currentUser) {
                $query->whereHas('country', fn ($q) => $q->whereIn('code', $currentUser->responsibleCountries()->pluck('code')->toArray()));
            })
            ->when($request->has('q'), function ($q) use ($request) {
                $query = "%" . $request->q . "%";
                $q->where(function ($q) use ($query) {
                    $q->where('name', 'like', $query);
                });
            })
            ->when($country !== "" && !is_null($country), function ($q) use ($request) {
                $q->where('country', $request->get('country'));
            })
            ->orderBy('updated_at', 'desc')
            ->paginate($numberPerPage);

        if($request->ajax()) {
            return view('templates.admin.teams.data', [
                'teams' => $teams,
                'countries' => $this->getCountry(),
            ]);
        }

        return view('templates.admin.teams.index', [
            'teams' => $teams,
            'countries' => $this->getCountry(),
        ]);
    }

    public function show(Team $team, Request $request)
    {
        $order = 'desc';

        if ($request->has('sort')) {
            $order = ['asc' => 'desc', 'desc' => 'asc'][$request->get('order', 'desc')] ?? 'desc';
        }

        $teamsApps = [];

        if ($team->apps->count() > 0) {
            $teamsApps = $team->apps;
        }

        return view('templates.admin.teams.show', [
            'team' => $team,
            'order' => $order,
            'teamsApps' => $teamsApps,
            'country' => Country::where('code', $team->country)->pluck('name')->first(),
        ]);
    }

    /**
     * Create a new team and return the view for creating a team.
     *
     * @return View The view for creating a team.
     */
    public function create(): View
    {
        return view('templates.admin.teams.create',
            [
                'countries' => $this->getCountry(),
            ]);
    }

    /**
     * Store a new team based on the provided request.
     *
     * @param TeamRequest $request The request containing the team data.
     * @return RedirectResponse Redirects to the team index page.
     */
    public function store(TeamRequest $request): RedirectResponse
    {
        $this->storeTeam($request);
        return redirect()->route('admin.team.index');
    }

    /**
     * Display the edit form for the specified team.
     *
     * @param Team $team The team to be edited.
     * @return View The view for editing the team.
     */
    public function edit(Team $team): View
    {
        return view('templates.admin.teams.edit', [
            'team' => $team,
            'countries' => $this->getCountry()
        ]);
    }

    /**
     * Update a team based on the provided team data and request.
     *
     * @param Team $team The team to be updated.
     * @param TeamRequest $request The request containing the team data.
     * @return RedirectResponse Redirects to the team index page.
     */
    public function update(Team $team, TeamRequest $request): RedirectResponse
    {
        $this->updateTeam($team, $request);
        return redirect()->route('admin.team.index');
    }

    /**
     * Destroys a team and associated data, including users, invites, and apps.
     *
     * @param Team $team The team to be destroyed
     * @return JsonResponse Returns JSON response indicating success
     */
    public function destroy(Team $team): JsonResponse
    {
        $teamMembers = $team->users->pluck('id')->toArray();
        $currentUsers = $team->users;
        $teamsInvites = TeamInvite::where('team_id', $team->id)->get();

        if ($teamsInvites) {
            $teamsInvites->each->delete();
        }

        if ($currentUsers) {
            $userIds = $currentUsers->pluck('id')->toArray();

            $currentUsers->each(function ($teamUser) use ($team) {
                ApigeeService::removeDeveloperFromCompany($team, $teamUser);
            });

            collect($userIds)->each(function ($id) use ($team) {
                Notification::create([
                    'user_id' => $id,
                    'notification' => "Your team <strong>{$team->name}</strong> has been deleted.",
                ]);
            });

            $team->users()->detach($teamMembers);
        }

        $appNamesToDelete = App::where('team_id', $team->id)->pluck('name')->toArray();

        if ($appNamesToDelete) {

            foreach ($appNamesToDelete as $appName) {
                $deletedApps = ApigeeService::delete("companies/{$team->username}/apps/{$appName}");

                if ($deletedApps->successful()) {
                    App::where('name', $appName)->delete();
                }
            }
        }

        ApigeeService::deleteCompany($team);
        $team->delete();

        return response()->json(['success' => true, 'code' => 200], 200);
    }

    public function remove(LeavingRequest $teamRequest, Team $team)
    {
        $loggedInUser = auth()->user();
        $data = $teamRequest->validated();
        $team = $this->getTeam($data['team_id']);
        $user = $this->getTeamUser($data['user_id']);

        abort_if(!$team, 424, 'The team could not be found');
        abort_if(!$loggedInUser->hasRole('admin') || !$loggedInUser->hasRole('opco'), 424, 'You are not authorized to remove a user from this team');

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
     * Make Team member Admin/User
     */
    public function roleUpdate(RoleUpdateRequest $roleRequest, Team $team)
    {
        $data = $roleRequest->validated();
        $user = User::find($data['user_id']);
        $role = Role::where('name', $data['role'])->first();
        $loggedInUser = auth()->user();

        abort_if(!$team, 424, 'The team could not be found');
        abort_if(!$loggedInUser->hasRole('admin') || !$loggedInUser->hasRole('opco'), 424, 'You are not authorized to remove a user from this team');

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
     * Invites a teammate using the provided InviteRequest data and team ID.
     *
     * @param InviteRequest $inviteRequest The request data for the invitation
     * @param int $id The ID of the team to invite the teammate to
     * @return JsonResponse
     */
    public function invite(InviteRequest $inviteRequest, int $id): JsonResponse
    {
        $data = $inviteRequest->validated();
        return $this->inviteTeammate($data, $id);
    }

    /**
     * @param TeamOwnerRequest $requestTeamOwnership
     * @param Team $team
     * @return JsonResponse
     */
    public function ownership(TeamOwnerRequest $requestTeamOwnership, Team $team): JsonResponse
    {
        $data = $requestTeamOwnership->validated();
        return $this->changeOwnership($data, $team);
    }
}
