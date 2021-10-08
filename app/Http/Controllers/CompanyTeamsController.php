<?php

namespace App\Http\Controllers;

use App\App;
use App\Http\Requests\Teams\InviteRequest;
use App\Mail\Invites\InviteExternalUser;
use App\Mail\Invites\RemoveUser;
use App\Team;
use App\User;
use App\Country;
use App\Product;


use App\Mail\TeamAppCreated;
use App\Services\TeamCompanyService;
use App\Http\Requests\TeamAppRequest;
use App\Http\Requests\Teams\LeaveTeamRequest;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Teams\DeleteTeamRequest;
use Mpociot\Teamwork\Events\UserInvitedToTeam;
use Mpociot\Teamwork\Events\UserLeftTeam;
use Mpociot\Teamwork\Exceptions\UserNotInTeamException;
use Mpociot\Teamwork\Facades\Teamwork;

/**
 * Class CompanyTeamsController
 *
 * @package App\Http\Controllers
 */
class CompanyTeamsController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->teams->isEmpty()) {
            return redirect()->route('teams.create');
        }

        $collectedTeams = [];

        foreach($request->user()->teams as $team){
            $collectedTeams[] = [
                'id' => $team->id,
                'name'=> $team->name,
                'country' => $team->country,
                'members' => $team->users->count(),
                'apps_count' => (function() use($team) {
                    $appsCount = 0;
                    $team->users->map(function($user) use (&$appsCount) {
                        $appsCount += $user->getDeveloperAppsCount();
                        return $appsCount;
                    });
                    return $appsCount;
                })(),
                'logo' => $team->logo,
            ];
        }

        return view('templates.teams.index', [
            'teams' => $collectedTeams,
        ]);
    }

    public function leave(LeaveTeamRequest $teamRequest)
    {
        $data = $teamRequest->validated();

        $user = User::find($data['user_id']);
        $team = Team::find($data['team_id']);

        if ($team->users()->detach($user->id)) {
            Mail::to($user->email)->send(new RemoveUser($team, $user));
            return response()->json(['success' => true,]);
        }

        return response()->json(['success' => false,]);
    }

    public function remove(LeaveTeamRequest $teamRequest)
    {
        $data = $teamRequest->validated();

        $user = User::find($data['user_id']);
        $team = Team::find($data['team_id']);

        $userDeleted = \DB::table('team_users')->delete(['user_id' => $user->id, 'team_id' => $team->id]);

        if ($userDeleted) {
            Mail::to($user->email)->send(new RemoveUser($team, $user));
            return response()->json(['success' => true,]);
        }

        return response()->json(['success' => false]);
    }

    public function invite(InviteRequest $inviteRequest)
    {
        $data = $inviteRequest->validated();

        $team = Team::find($data['team_id']);
        $user = User::where('email', '=', $data['invitee'])->first();

        if (is_null($user)) {
            $teamOwner = User::find($team->owner_id);
            Mail::to($teamOwner->email)->send(new InviteExternalUser($team, $data['invitee']));
            return response()->json(['success' => true]);
        } elseif($user->exists) {
            Teamwork::inviteToTeam( $user->email, $team, function( $invite ) use($user) {
                event(new UserInvitedToTeam($invite));
            });
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
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

    public function store(TeamCompanyService $teamService, TeamAppRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        $teamData = [
            'name' => $validated['name'],
            'url' => $validated['url'],
            'contact' => $validated['contact'],
            'description' => $validated['description'],
        ];

        if (isset($validated['country'])) {
            $teamData['country'] = Country::where('code', $validated['country'])->value('name');
        }

        $teamData['logo'] = '/storage/profile/profile-' . rand(1, 32) . '.svg';

        if (isset($validated['logo-file']) && !is_string($validated['logo-file'])) {
            $fileName =  'logo.' . $request->file('logo-file')->extension();
            $path = $request->file('logo-file')->storeAs(
                "public/team/{$user->username}",
                $fileName
            );
            $teamData['logo'] = str_replace('public', '/storage', $path);
        }

        $team = $teamService->createUserTeam($user, $teamData)->pop();

        if ($result = $teamService->createDeveloperTeam($user, $team->toArray())) {
            if ($result->status() === 201) {
                if (!empty($data['invitations'])) {
                    foreach ($data['invitations'] as $emailAddress) {
                        $user = User::where('email', '=', $emailAddress)->first();
                        if ($user->exists()) {
                            Teamwork::inviteToTeam( $user->email , $team);
                        } else {
                            Mail::to($emailAddress)->send(new InviteExternalUser($team, $emailAddress));
                        }
                    }
                }

                Mail::to($user->email)->send(new TeamAppCreated($team));

                return redirect()->route('teams.listing')->with('alert', "success:{$team->name} has been created!");
            }
        }

        return redirect()->back()->with('alert', "error:Could not create requested Team. Please try again.");
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $user->load(['responsibleCountries']);

        $countries = Country::whereIn('code', Country::all()->pluck('code'))->orderBy('name')->pluck('name', 'code');

        return view('templates.teams.create', [
            'colleagues' => User::all()->pluck('email'),
            'userOwnsTeam' => !is_null($user->current_team_id),
            'hasTeams' => $user->teams()->count() > 0,
            'countries' => $countries,
        ]);
    }

    public function destroy(TeamCompanyService $companyService, DeleteTeamRequest $request)
    {
        $validated = $request->validated();

        $user = $request->user();
        $team = Team::where('name', $validated['name'])->first();

        $teamDeleted = false;
        if ($user->isTeamOwner($team)) {
            $companyService->deleteCompanyAppKeys($user->developer_id, $team->name);
            $teamDeleted = true;
        }

        if ($teamDeleted) {
            $team->delete();

            return redirect(route('teams.index'));
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Could not remove team. Please try again.'
            ]);
        }
    }
}
