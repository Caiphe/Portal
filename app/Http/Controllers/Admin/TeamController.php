<?php

namespace App\Http\Controllers\Admin;

use App\Concerns\Teams\InviteActions;
use App\Concerns\Teams\InviteRequests;
use App\Country;
use App\Http\Helpers\Teams\TeamsCompanyTrait;
use App\Http\Helpers\Teams\TeamsHelper;
use App\Product;
use App\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    use  InviteRequests, InviteActions, TeamsCompanyTrait;
    private $TeamsHelper;

    public function index ()
    {
        $teams = Team::all();
        return view('templates.admin.team.index', [
            'teams' => $teams
        ]);
    }

    public function create (Request $request)
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
        return view('templates.admin.team.create',
            [
                'team' => $invitingTeam,
                'teamInvite' => $teamInvite,
                'countries' => $countries,
                'user' => $user
            ]);
    }

    public function store (Request $request)
    {
        $test = $this->storeTeam($request);
        dd($test);

        Team::create($request->all());
        return redirect()->route('admin.team.index');
    }

    public function show (Team $team)
    {
        return view('templates.admin.team.show', [
            'team' => $team
        ]);
    }

    public function edit (Team $team)
    {
        return view('templates.admin.team.edit', [
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
        $team->delete();
        return redirect()->route('admin.team.index');
    }
}
