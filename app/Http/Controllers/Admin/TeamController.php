<?php

namespace App\Http\Controllers\Admin;

use App\App;
use App\Team;
use App\User;
use App\Country;
use App\Product;
use App\Notification;
use Illuminate\Http\Request;
use App\Traits\CountryTraits;
use App\Services\ApigeeService;
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


    public function index (Request $request)
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

    public function show(Team $team)
    {
        return view('templates.admin.teams.show',[
            'team' => $team
        ]);
    }

    public function create ()
    {
        return view('templates.admin.teams.create',
            [
                'countries' => $this->getCountry(),
            ]);
    }

    public function store (TeamRequest $request)
    {
        $this->storeTeam($request);
        return redirect()->route('admin.team.index');
    }

    public function edit (Team $team)
    {
        return view('templates.admin.teams.edit', [
            'team' => $team
        ]);
    }

    public function update (Team $team, Request $request)
    {
        $team->update($request->all());
        return redirect()->route('admin.team.index');
    }

    public function destroy (Team $team)
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
