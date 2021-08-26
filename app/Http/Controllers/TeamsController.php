<?php

namespace App\Http\Controllers;

use App\App;
use App\Http\Requests\CreateAppRequest;
use App\User;
use App\Country;
use App\TeamMember;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Services\TeamsService;
use App\Http\Requests\TeamRequest;

class TeamsController extends Controller
{
    public function index(Request $request)
    {
        return view('templates.teams.index', [
            'teams' => $request->user()->teams
        ]);
    }

    public function show($id, Request $request)
    {
        $apps = App::with(['products.countries', 'country', 'developer'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('status');

        $team = $request->user()->teams()->where('id', $id)->first();

        return view('templates.teams.show', [
            'approvedApps' => $apps['approved'] ?? [],
            'revokedApps' => $apps['revoked'] ?? [],
            'team' => $team,
        ]);
    }

    public function store(CreateAppRequest $appRequest, TeamRequest $request, TeamsService $teamsService)
    {
        $user = $request->user();

        if ($request->ajax()) {

            $data = $request->validated();

            $response = $teamsService->handleFileUpload($request);

            if ($response['success']) {
                $data = array_merge($data, ['filename' => $response['filename']]);
            }

            $userTeam = $teamsService->createUserTeam($user, $data);

            $app = new App([
                'name'
            ]);

            $result = $teamsService->storeOrUpdateApp($appRequest, $userTeam->toArray());

        }
    }

    public function create(Request $request)
    {
        $user = $request->user();

        $user->load(['responsibleCountries']);

        $isCompanyTeam = false;

        $countryList = Country::all()->pluck('code');
        if ($user->responsibleCountries->count() > 0 && $user->hasRole('admin')) {
            $countryList = $user->responsibleCountries->pluck('code');
            $isCompanyTeam = true;
        }

        $countries = Country::whereIn('code', $countryList)->orderBy('name')->pluck('name', 'code');

        return view('templates.teams.create', [
            'colleagues' => User::all()->pluck('email'),
            'userOwnsTeam' => !is_null($user->current_team_id),
            'hasTeams' => $user->teams()->count() > 0,
            'isCompanyTeam' => $isCompanyTeam,
            'countries' => $countries,
        ]);
    }
}
