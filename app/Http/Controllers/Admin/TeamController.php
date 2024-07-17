<?php

namespace App\Http\Controllers\Admin;

use App\App;
use App\Http\Requests\TeamUpdateRequest;
use App\Team;
use App\User;
use App\Country;
use App\Product;
use App\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Traits\CountryTraits;
use App\Services\ApigeeService;
use Illuminate\View\View;
use Mpociot\Teamwork\TeamInvite;
use App\Http\Controllers\Controller;
use App\Concerns\Teams\InviteActions;
use App\Concerns\Teams\InviteRequests;
use App\Http\Helpers\Teams\TeamsHelper;
use App\Http\Requests\Admin\TeamRequest;
use App\Http\Helpers\Teams\TeamsCompanyTrait;

class TeamController extends Controller
{
    use  InviteRequests, InviteActions, TeamsCompanyTrait, CountryTraits;

    /**
     * Retrieves a paginated list of teams based on the provided request parameters.
     *
     * @param Request $request The HTTP request object containing query parameters.
     * @return View The view displaying the list of teams.
     */
    public function index (Request $request): View
    {
        $country = $request->get('country', "");
        $numberPerPage = (int)$request->get('number_per_page', '15');

        $teams = Team::with(['apps', 'users'])
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

        return view('templates.admin.teams.index', [
            'teams' => $teams,
            'countries' => $this->getCountry(),

        ]);
    }

    /**
     * Display the specified team.
     *
     * @param Team $team The team to be displayed.
     * @return View The view displaying the team details.
     */
    public function show(Team $team): View
    {
        return view('templates.admin.teams.show',[
            'team' => $team
        ]);
    }
    /**
     * Create a new team and return the view for creating a team.
     *
     * @return View The view for creating a team.
     */
    public function create (): View
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
    public function store (TeamRequest $request): RedirectResponse
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
    public function edit (Team $team): View
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
    public function update (Team $team, TeamRequest $request): RedirectResponse
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
    public function destroy (Team $team): JsonResponse
    {
        $teamMembers = $team->users->pluck('id')->toArray();
        $currentUsers = $team->users;
        $teamsInvites = TeamInvite::where('team_id', $team->id)->get();

        if ($teamsInvites) {
            $teamsInvites->each->delete();
        }

        if($currentUsers){
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
