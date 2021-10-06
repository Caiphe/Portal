<?php

namespace App\Http\Controllers;

use App\App;
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
use Mpociot\Teamwork\Events\UserLeftTeam;
use Mpociot\Teamwork\Exceptions\UserNotInTeamException;

/**
 * Class CompanyTeamsController
 *
 * @package App\Http\Controllers
 */
class CompanyTeamsController extends Controller
{
    /**
     * Teams, the User is affilliated to
     *
     * @param Request $request
     * @return Factory
     */
    public function index(Request $request)
    {
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
            ];
        }

        return view('templates.teams.index', [
            'teams' => $collectedTeams,
        ]);
    }

    /**
     * User leaves Team
     *
     * @param LeaveTeamRequest $teamRequest
     * @return JsonResponse
     */
    public function leave(LeaveTeamRequest $teamRequest)
    {
        $data = $teamRequest->validated();

        $team = Team::find($data['team_id'])->first();
        $member = User::where('developer_id', $data['member'])->first();

        if ($team->hasUser($member)) {

            try {

                $member->switchTeam(null);
                event(new UserLeftTeam($invitee, $validated['team_id']));

            } Catch(UserNotInTeamException $exception) {
                return response()->json(['error' => 'Given team is not allowed for the user.']);
            }
            return response()->json(['message' => 'Successfully left Team'], 200);
        }
        return response()->json(['error' => 'Could not leave the Team']);
    }

    /**
     * Shows a Team with its members, including those
     * awaiting invite, request and/or ownership transfers
     *
     * @param $id
     * @param Request $request
     * @return Factory
     */
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
            'users' => $team->users,
            'team' => $team,
        ]);
    }

    public function store(TeamCompanyService $companyService, TeamAppRequest $request)
    {
        $validated = $request->validated();

        $countryCodes = Country::pluck('iso', 'code');
        $products = Product::whereIn('name', $request->get('products'))
            ->pluck('attributes', 'name');

        $productIds = $attrs = [];

        foreach ($products as $name => $attributes) {
            $attrs = json_decode($attributes, true);
            $productIds[] = $attr['SandboxProduct'] ?? $name;
        }

        $data = [
            'name' => Str::slug($validated['display_name']),
            'apiProducts' => $productIds,
            'attributes' => [
                [
                    'name' => 'DisplayName',
                    'value' => $validated['display_name'],
                ],
                [
                    'name' => 'Description',
                    'value' => $validated['description'],
                ],
                [
                    'name' => 'Country',
                    'value' => $validated['country'],
                ],
                [
                    'name' => 'location',
                    'value' => $countryCodes[$validated['country']] ?? "",
                ],
            ],
            'callbackUrl' => $validated['url'],
            'keyExpiresIn' => -1,
        ];

        $user = $request->user();

        if (($user->hasRole('admin') || $user->hasRole('opco')) && $request->has('app_owner')) {
            $appOwner = User::where('email', $request->get('app_owner'))->first();
        } else {
            $appOwner = $user;
        }

        $createdResponse = $companyService->devOwnedAppCreate($appOwner->developer_id, $data);

        if (strpos($createdResponse, 'duplicate key') !== false) {
            return response()->json(['success' => false, 'message' => 'There is already a Developer app with that name.'], 409);
        }

        $app = App::create([
            "aid" => $createdResponse['appId'],
            "name" => $createdResponse['name'],
            "display_name" => $validated['display_name'],
            "callback_url" => $createdResponse['callbackUrl'],
            "attributes" => $attributes,
            "credentials" => $createdResponse['credentials'],
            "developer_id" => $createdResponse['developerId'],
            "status" => $createdResponse['status'],
            "description" => $validated['description'],
            "country_code" => $validated['country'],
            "updated_at" => date('Y-m-d H:i:s', $createdResponse['lastModifiedAt'] / 1000),
            "created_at" => date('Y-m-d H:i:s', $createdResponse['createdAt'] / 1000),
        ]);

        $app->products()->sync(
            array_reduce(
                $createdResponse['credentials'][0]['apiProducts'],
                function ($carry, $apiProduct) {
                    $pivotOptions = ['status' => $apiProduct['status']];

                    if ($apiProduct['status'] === 'approved') {
                        $pivotOptions['actioned_at'] = date('Y-m-d H:i:s');
                    }

                    $carry[$apiProduct['apiproduct']] = $pivotOptions;
                    return $carry;
                },
                []
            )
        );

        $team = $companyService->createUserTeam($teamOwner, $validated);

        $app->update(['team_id' => $team->id]);

        $opcoUserEmails = $app->country->opcoUser->pluck('email')->toArray();
        if (empty($opcoUserEmails)) {
            $opcoUserEmails = env('MAIL_TO_ADDRESS');
        }

        Mail::to($opcoUserEmails)->send(new TeamAppCreated($app));

        if ($request->ajax()) {
            return response()->json(['response' => $createdResponse]);
        }

        return redirect(route('teams.index'));
    }

    /**
     * Create a new Team
     *
     * @param Request $request
     * @return Factory
     */
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

    /**
     * Deletes a team
     *
     * @param TeamCompanyService $companyService
     * @param DeleteTeamRequest $request
     * @return RedirectResponse|JsonResponse
     */
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

    /**
     * Upload a file and prepare the file name to be saved on local storage
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function fileUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'image'],
            'type' => 'required',
        ]);

        if ($validator->validate()) {
            $fileExt = $request->file('file')->extension();
            $savedFileName = md5(uniqid(microtime())) . '.' . $fileExt;

            $request->file('file')->storeAs('teams/' . $request->get('type'), $savedFileName);

            return response()->json([
                'success' => false,
                'data' => ['file_name' => $savedFileName],
                'message' => 'Could not remove team. Please try again.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Could not upload requested file. Please try again.'
            ]);
        }
    }
}
