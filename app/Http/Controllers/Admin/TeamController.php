<?php

namespace App\Http\Controllers\Admin;

use App\Team;
use Illuminate\Http\Request;
use App\Traits\CountryTraits;
use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    use CountryTraits;
    
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
       

        return view('templates.admin.team.index', [
            'teams' => $teams,
            'countries' => $this->getCountry(),

        ]);
    }

    public function show(Team $team)
    {
        return view('templates.admin.team.show',[
            'team' => $team
        ]);
    }
}
