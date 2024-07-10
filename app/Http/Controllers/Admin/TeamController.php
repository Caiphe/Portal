<?php

namespace App\Http\Controllers\Admin;

use App\Concerns\Teams\InviteActions;
use App\Concerns\Teams\InviteRequests;
use App\Country;
use App\Http\Helpers\Teams\TeamsCompanyTrait;
use App\Http\Helpers\Teams\TeamsHelper;
use App\Http\Requests\Admin\TeamRequest;
use App\Product;
use App\Services\ApigeeService;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use App\Traits\CountryTraits;
use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    use  InviteRequests, InviteActions, TeamsCompanyTrait, CountryTraits;


    public function index (Request $request)
    {
        $sort = '';
        $order = $request->get('order', 'desc');
        $numberPerPage = (int)$request->get('number_per_page', '15');

        $teams = Team::with(['apps', 'users'])
            ->when($request->has('q'), function ($q) use ($request) {
                $query = "%" . $request->q . "%";
                $q->where(function ($q) use ($query) {
                    $q->where('name', 'like', $query);
                });
            })
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
        $locations = Product::isPublic()
            ->WhereNotNull('locations')
            ->Where('locations', '!=', 'all')
            ->select('locations')
            ->get()
            ->implode('locations', ',');

        $locations = array_unique(explode(',', $locations));
        $countries = Country::whereIn('code', $locations)->orderBy('name')->pluck('name', 'code');

        return view('templates.admin.teams.create',
            [
                'countries' => $countries,
            ]);
    }

    public function store (TeamRequest $request)
    {
        $this->storeTeam($request);
        return redirect()->route('admin.teams.index');
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
        $team->delete();
        return redirect()->route('admin.team.index');
    }
}
