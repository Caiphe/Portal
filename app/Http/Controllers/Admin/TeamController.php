<?php

namespace App\Http\Controllers\Admin;

use App\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    public function index ()
    {
        $teams = Team::all();
        return view('templates.admin.team.index', [
            'teams' => $teams
        ]);
    }
}
