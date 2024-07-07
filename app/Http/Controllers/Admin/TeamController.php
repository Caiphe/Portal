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

        $teams = Team::all();

        if ($request->has('sort')) {
            $sort = $request->get('sort');

            if ($sort === 'category.title') {
                $teams->orderBy('category_title', $order);
            } else {
                $teams->orderBy($sort, $order);
            }

            $order = ['asc' => 'desc', 'desc' => 'asc'][$order] ?? 'desc';
        }

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
