<?php

namespace App\Http\Controllers;

use App\App;
use App\Team;
use App\User;
use App\Country;

use App\Mail\Invites\RemoveUser;
use App\Mail\Invites\InviteExternalUser;
use App\Http\Requests\TeamRequest;
use App\Http\Requests\Teams\InviteRequest;
use App\Http\Requests\Teams\LeaveTeamRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Facades\Teamwork;
use Mpociot\Teamwork\Events\UserInvitedToTeam;
use Mpociot\Teamwork\TeamInvite;

/**
 * Class CompanyTeamsController
 *
 * @package App\Http\Controllers
 */
class CompanyTeamsController extends Controller
{
    public function index(Request $request)
    {
        $collectedTeams = [];

        foreach($request->user()->teams as $team){
            $collectedTeams[] = [
                'id' => $team->id,
                'name'=> $team->name,
                'country' => $team->country,
                'members' => $team->users->count(),
                'apps_count' => App::where(['team_id' => $team->id])->get()->count(),
                'logo' => $team->logo,
            ];
        }

        if (empty($collectedTeams)) {
            return redirect()->route('teams.create');
        } else {
            return view('templates.teams.index', [
                'teams' => $collectedTeams,
            ]);
        }
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

    public function ownership(InviteRequest $inviteRequest)
    {
        $data = $inviteRequest->validated();

        $team = Team::find($data['team_id']);
        $user = User::where('email', '=', $data['invitee'])->first();

        $invite = new TeamInvite();

        $invite->user_id = $user->id;
        $invite->team_id = $team->id;
        $invite->type = 'ownership';
        $invite->email = $data['invitee'];
        $invite->accept_token = md5(uniqid(microtime()));
        $invite->deny_token = md5(uniqid(microtime()));
        $invite->save();

        return response()->json(['success' => true]);
    }

    public function invite(InviteRequest $inviteRequest)
    {
        $data = $inviteRequest->validated();

        $team = Team::find($data['team_id']);
        $user = User::where('email', '=', $data['invitee'])->first();

        if (is_null($user) && !is_null($team)) {
            $teamOwner = User::find($team->owner_id);

            $invite = new TeamInvite();

            $invite->user_id = $teamOwner->id;
            $invite->team_id = $team->id;
            $invite->type = 'invite';
            $invite->email = $data['invitee'];
            $invite->accept_token = md5(uniqid(microtime()));
            $invite->deny_token = md5(uniqid(microtime()));
            $invite->save();

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
        $team = $request->user()->teams()->where('id', $id)->first();

        $apps = App::with(['products.countries', 'country', 'developer'])
            ->where('team_id', $team->id)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('status');

        return view('templates.teams.show', [
            'approvedApps' => $apps['approved'] ?? [],
            'revokedApps' => $apps['revoked'] ?? [],
            'team' => $team,
        ]);
    }

    public function store(TeamRequest $request)
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

        if (isset($validated['logo_file']) && !is_string($validated['logo_file'])) {
            $fileName =  'logo.' . $request->file('logo_file')->extension();
            $path = $request->file('logo_file')->storeAs(
                "public/team/{$user->username}",
                $fileName
            );
            $teamData['logo'] = str_replace('public', '/storage', $path);
        }

        $team = Team::create([
            'name' => $teamData['name'],
            'url' => $teamData['url'],
            'contact' => $teamData['contact'],
            'country' => $teamData['country'],
            'description' => $teamData['description'],
            'logo' => $teamData['logo'],
            'owner_id' => $user->getKey()
        ]);

        $user->attachTeam($team);

        if (!empty($data['team_members'])) {
            foreach ($data['team_members'] as $emailAddress) {
                $user = User::where('email', $emailAddress)->first();
                if (is_null($user)) {
                    Mail::to($emailAddress)->send(new InviteExternalUser($team, $emailAddress));
                } else {
                    Teamwork::inviteToTeam( $user->email, $team);
                }
            }
        }

        if (strpos(url()->previous(), 'teams/create') !== false) {
            return redirect()->route('teams.listing')->with('alert', "success:{$team->name} has been created");
        }

        return redirect()->back()->with('alert', "success:{$team->name} has been created");
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $user->load(['responsibleCountries']);

        $countries = Country::whereIn('code', Country::all()->pluck('code'))->orderBy('name')->pluck('name', 'code');

        $teamInvite = TeamInvite::where('email', $user->email)
            ->first();

        $team = null;
        if ( $teamInvite ) {
            $team = Team::find($teamInvite->team_id);
        }

        return view('templates.teams.create', [
            'colleagues' => User::all()->pluck('email'),
            'userOwnsTeam' => !is_null($user->current_team_id),
            'hasTeams' => $user->teams()->count() > 0,
            'teamInvite' => $teamInvite,
            'countries' => $countries,
            'team' => $team,
        ]);
    }

    public function accept(Request $request)
    {
        $invite = Teamwork::getInviteFromAcceptToken( $request->get('token') );

        if( $invite->type === 'ownership') {
            $team = Team::find($invite->team_id);

            $teamOwner = User::where('email', $team->owner->email)->first();
            $newTeamOwner = User::where('email', $invite->email)->first();

            $teamOwner->detachTeam($team);
            $newTeamOwner->attachTeam($team);

            Teamwork::acceptInvite( $invite );
        }else{
            Teamwork::acceptInvite( $invite );
        }

        if (!$invite->exists) {
            return redirect()->route('teams.listing')->with('success:Invite successfully accepted.');
        } else {
            return redirect()->route('teams.listing')->with('error:Team invite could not be accepted. Contact administrator.');
        }
    }

    public function reject(Request $request)
    {
        $invite = Teamwork::getInviteFromDenyToken( $request->get('token') );

        if( $invite ) {
            Teamwork::denyInvite( $invite );
        }

        if (!$invite->exists) {
            return redirect()->route('teams.listing')->with('success:Invite successfully denied.');
        } else {
            return redirect()->route('teams.listing')->with('error:Team invite could not be denied. Contact administrator.');
        }
    }

    public function edit(Request $request, $id)
    {
        $user = $request->user();
        $user->load(['responsibleCountries']);

        $countries = Country::whereIn('code', Country::all()->pluck('code'))->orderBy('name')->pluck('name', 'code');

        return view('templates.teams.update', [
            'countries' => $countries,
            'team' => Team::find($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'action' => 'required',
            'name' => 'required',
            'url' => 'required',
            'contact' => 'required',
            'country' => 'required',
            'description' => 'required',
        ]);

        if (!$validator->fails()) {

            $team = Team::find($id);

            if ($request->has('team_members')) {
                foreach ($request->get('team_members') as $emailAddress) {
                    $user = User::where('email', $emailAddress)->first();
                    if (is_null($user)) {
                        Mail::to($emailAddress)->send(new InviteExternalUser($team, $emailAddress));
                    } else {
                        Teamwork::inviteToTeam( $user->email, $team);
                    }
                }
            }

            $data = $request->only(['name', 'url', 'contact', 'description',]);

            if ($request->has('country')) {
                $data['country'] = Country::where('code', $request->get('country'))->value('name');
            }

            $team->update($data);

            return redirect()->route('team.show', $team->id)
                ->with('success: Your team was successfully updated.');
        } else {
            return redirect()->back()
                ->with('error: Your team could not be successfully updated.');
        }
    }
}
