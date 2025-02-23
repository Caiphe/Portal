<?php

namespace App\Http\Controllers;

use App\App;
use App\Country;
use App\Http\Requests\AppAttributesRequest;
use App\Http\Requests\CreateAppRequest;
use App\Http\Requests\DeleteAppRequest;
use App\Http\Requests\KycRequest;
use App\Mail\CredentialRenew;
use App\Mail\GoLiveMail;
use App\Mail\NewApp;
use App\Mail\TeamAppCreated;
use App\Mail\UpdateApp;
use App\Notification;
use App\Product;
use App\Services\ApigeeService;
use App\Services\Kyc\KycService;
use App\Team;
use App\User;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Sentry\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userTeams = $user->teams;

        $ownershipInvite = DB::table('team_invites')->where([
            'email' => $user->email,
            'type' => 'ownership'
        ])->first();

        $teamInvite = DB::table('team_invites')->where([
            'email' => $user->email,
            'type' => 'invite'
        ])->first();

        $team = null;
        if ($teamInvite) {
            $team = Team::find($teamInvite->team_id);
        }

        $ownershipTeam = null;
        if ($ownershipInvite) {
            $ownershipTeam = Team::find($ownershipInvite->team_id);
        }

        $apps = App::with(['products.countries', 'country', 'developer'])
            ->byUserEmail($user->email)
            ->when($userTeams, fn($q) => $q->orWhereIn('team_id', $userTeams->pluck('id')))
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('status');

        return view('templates.apps.index', [
            'approvedApps' => $apps['approved'] ?? [],
            'revokedApps' => $apps['revoked'] ?? [],
            'ownershipInvite' => $ownershipInvite,
            'ownershipTeam' => $ownershipTeam ?? null,
            'teamInvite' => $teamInvite,
            'team' => $team,
        ]);
    }

    public function checkAppName(Request $request)
    {
        $user = $request->user();
        $appName = Str::slug($request->name);
        $appNameCheck = App::where('name', $appName)->where('developer_id', $user->developer_id)->exists();

        if ($appNameCheck) {
            return response()->json(['success' => true], 409);
        }

        return response()->json(['success' => false], 422);
    }

    public function checkAppDuplicateName(Request $request): JsonResponse
    {
        $user = $request->user();
        $appName = Str::slug($request->name);
        $appNameCheck = App::where('name', $appName)->where('developer_id', $user->developer_id)->exists();

        if ($appNameCheck) {
            return response()->json(['duplicate' => true], 200);
        }

        return response()->json(['duplicate' => false], 200);
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $userOwnTeams = $user->teams;
        $product = null;
        $product_sanitized = Str::slug(htmlspecialchars($request->product, ENT_NOQUOTES));

        $assignedProducts = $user->assignedProducts()->with('category')->get();
        $products = Product::with(['category', 'countries'])
            ->where('category_cid', '!=', 'misc')
            ->basedOnUser($user)
            ->when($request->has('product'), function ($q) use (&$product, $product_sanitized) {
                $product = Product::with(['countries'])->where('slug', $product_sanitized)->first();
                abort_if($product === null, 404);
                $productLocations = $product->countries->pluck('code')->toArray();
                $q->byLocations($productLocations);
            })
            ->get()
            ->merge($assignedProducts);

        $prodGroup = Product::with(['category', 'countries'])
            ->where('category_cid', '!=', 'misc')
            ->basedOnUser($request->user())
            ->get()
            ->merge($assignedProducts);

        $locations = $product->locations ?? $products->pluck('locations')->implode(',');
        $locations = array_unique(explode(',', $locations));
        $countries = Country::whereIn('code', $locations)->orderBy('name')->pluck('name', 'code');
        $products = $products->sortBy('display_name')->groupBy('category.title')->sortKeys();
        $productGroups = $prodGroup->pluck('group')->unique()->toArray();
        $productCategories = $prodGroup->pluck('category.title', 'category.slug');

        return view('templates.apps.create', [
            'productSelected' => $product,
            'products' => $products,
            'productCategories' => $productCategories,
            'teams' => $userOwnTeams,
            'countries' => $countries ?? '',
            'user' => $user,
            'productGroups' => $productGroups
        ]);
    }

    public function store(CreateAppRequest $request)
    {
        $user = $request->user();
        $count = App::where('developer_id', auth()->user()->developer_id)
            ->where('created_at', '>=', now()->startOfDay())
            ->count();

        $userRoles = array_unique(explode(',', $user->getRolesListAttribute()));

        if ($count >= 5 && !in_array('Admin', $userRoles)) {
            abort('429');
        }

        $validated = $request->validated();
        $channels = implode(', ', $validated['channels']);
        $countriesByCode = Country::pluck('iso', 'code');
        $products = Product::whereIn('name', $validated['products'])->pluck('attributes', 'name');
        $productIds = [];
        $attr = [];
        $team = null;

        foreach ($products as $name => $attributes) {
            $attr = json_decode($attributes, true);
            $productIds[] = $attr['SandboxProduct'] ?? $name;
        }

        if (count($productIds) !== count($validated['products'])) {
            return response()->json(['success' => false, 'message' => 'There was a problem finding your product(s). Please try again'], 417);
        }

        if (isset($validated['team_id']) && $validated['team_id']) {
            $team = Team::find($validated['team_id']);
        }

        $countTeamApps = 0;
        if ($team) {
            $countTeamApps = App::where('team_id', $team->id)
                ->where('created_at', '>=', now()->startOfDay())
                ->count();
        }

        abort_if($countTeamApps >= 5, 429, "Action not allowed.");

        $attributes = ApigeeService::formatAppAttributes($validated['attribute']);
        $attributes = ApigeeService::formatToApigeeAttributes($attributes);

        $attributes = array_merge($attributes, [
            [
                'name' => 'DisplayName',
                'value' => $validated['display_name'],
            ],
            [
                'name' => 'Description',
                'value' => $validated['description'],
            ],
            [
                'name' => 'EntityName',
                'value' => $validated['entity_name'] ?? "",
            ],
            [
                'name' => 'Channels',
                'value' => $channels ?? "",
            ],
            [
                'name' => 'ContactNumber',
                'value' => $validated['contact_number'] ?? "",
            ],
            [
                'name' => 'Country',
                'value' => $validated['country'],
            ],
            [
                'name' => 'location',
                'value' => $countriesByCode[$validated['country']] ?? "",
            ],
            [
                'name' => 'TeamName',
                'value' => $team->name ?? "",
            ],
        ]);

        $data = [
            'name' => Str::slug($validated['display_name']),
            'apiProducts' => $productIds,
            'keyExpiresIn' => -1,
            'attributes' => $attributes,
            'callbackUrl' => $validated['url'],
        ];


        if (($user->hasRole('admin') || $user->hasRole('opco')) && $request->has('app_owner')) {
            $appOwner = User::where('email', $request->get('app_owner'))->first();
        } else {
            $appOwner = $user;
        }

        $createdResponse = ApigeeService::createApp($data, $appOwner, $team);

        if (strpos($createdResponse, 'duplicate key') !== false) {
            return response()->json(['success' => false, 'message' => 'There is already an app with that name.'], 409);
        }

        if ($createdResponse->failed()) {
            $reasonMsg = $createdResponse['message'] ?? 'There was a problem creating your app. Please try again later.';

            if ($request->ajax()) {
                return response()->json(['message' => $reasonMsg], $createdResponse->status());
            }

            return redirect()->back()->with('alert', "error:{$reasonMsg}");
        }

        $attributes = ApigeeService::getAppAttributes($createdResponse['attributes']);

        $app = App::create([
            "aid" => $createdResponse['appId'],
            "name" => $createdResponse['name'],
            "display_name" => $validated['display_name'],
            "callback_url" => $createdResponse['callbackUrl'],
            "attributes" => $attributes,
            "credentials" => $createdResponse['credentials'],
            "developer_id" => $appOwner['developer_id'],
            "team_id" => $team->id ?? null,
            "status" => $createdResponse['status'],
            "description" => $validated['description'],
            "country_code" => $validated['country'],
            "updated_at" => date('Y-m-d H:i:s', $createdResponse['lastModifiedAt'] / 1000),
            "created_at" => date('Y-m-d H:i:s', $createdResponse['createdAt'] / 1000),
            "entity_name" => $validated['entity_name'] ?? "",
            "channels" => $channels ?? "",
            "contact_number" => $validated['contact_number'] ?? "",
        ]);

        if (($user->hasRole('admin') || $user->hasRole('opco')) && $request->has('app_owner')) {
            $appOwner = User::where('email', $request->get('app_owner'))->first();

            Notification::create([
                'user_id' => $appOwner['id'],
                'notification' => "An admin has created an app <strong>{$validated['display_name']}</strong> for you please navigate to your <a href='/apps'>apps</a> for more info.",
            ]);
        }

        if ($team) {
            event(new TeamAppCreated($team));

            $appUsers = $team->users->pluck('id')->toArray();
            foreach ($appUsers as $user) {
                Notification::create([
                    'user_id' => $user,
                    'notification' => "New app <strong> {$app->display_name} </strong> has been created under your team <strong> {$team->name} </strong>. Please navigate to your <a href='/apps'>apps</a> to view.",
                ]);
            }
        }

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

        $opcoUserEmails = $app->country->opcoUser->pluck('email')->toArray();

        if (empty($opcoUserEmails)) {
            $opcoUserEmails = config('mail.mail_to_address');
        }
        Mail::to($opcoUserEmails)->send(new NewApp($app));

        if ($request->ajax()) {
            return response()->json(['response' => $createdResponse]);
        }

        return redirect(route('app.index'));
    }

    public function edit($app)
    {
        $user = auth()->user();
        $userTeams = $user->teams()->pluck('id')->toArray();
        $app = App::where('slug', $app)->where(
            fn($q) => $q->where('developer_id', auth()->user()->developer_id)
                ->orWhereIn('team_id', $userTeams)
        )->firstOrFail();
        $products = Product::with('category')
            ->where('category_cid', '!=', 'misc')
            ->where(fn($q) => $q->basedOnUser(auth()->user())->orWhereIn('pid', $app->products->where('environments', '!=', 'sandbox')->pluck('pid')->toArray()))
            ->get();
        $assignedProducts = $user->assignedProducts()->with('category')->get();

        $prodGroup = Product::with(['category', 'countries'])
            ->where('category_cid', '!=', 'misc')
            ->basedOnUser($user)
            ->get()
            ->merge($assignedProducts);
        $userOwnTeams = $user->teams;

        $productCategories = $prodGroup->pluck('category.title', 'category.slug');
        $countryCodes = $products->pluck('locations')->implode(',');
        $countries = Country::whereIn('code', explode(',', $countryCodes))->pluck('name', 'code');
        $productGroups = $prodGroup->pluck('group')->unique()->toArray();
        $products = $products->merge($assignedProducts)->sortBy('category.title')->groupBy('category.title');

        $app->load('products', 'country');
        $credentials = $app->credentials;
        $selectedProducts = ApigeeService::getLatestCredentials($credentials)['apiProducts'] ?? [];

        if (count($credentials) === 1 && $app->products->filter(fn($prod) => isset($prod->attributes['ProductionProduct']))->count() > 0) {
            $selectedProducts = $app->products->map(fn($prod) => $prod->attributes['ProductionProduct'] ?? $prod->name)->toArray();
        }

        return view('templates.apps.edit', [
            'products' => $products,
            'countries' => $countries ?? '',
            'data' => $app,
            'selectedProducts' => $selectedProducts,
            'user' => $user,
            'productCategories' => $productCategories,
            'productGroups' => $productGroups,
            'teams' => $userOwnTeams,
        ]);
    }

    public function update($app, CreateAppRequest $request)
    {
        $user = auth()->user();
        $userTeams = $user->teams()->pluck('id')->toArray();
        $app = App::where('slug', $app)->where(
            fn($q) => $q->where('developer_id', $user->developer_id)
                ->orWhereIn('team_id', $userTeams)
        )->firstOrFail();
        $team = null;

        $previewName = $app->display_name;

        $validated = $request->validated();
        $channels = implode(', ', $validated['channels']);
        $app->load('products', 'team');
        $appTeam = $app->team ?? null;
        $credentials = ApigeeService::getLatestCredentials($app->credentials);
        $products = Product::whereIn('name', $validated['products'])->get();
        $sandboxProducts = is_null($app->live_at) ? $products : $products->diff($app->products);
        $hasSandboxProducts = $sandboxProducts->filter(function ($prod) {
                return strpos($prod->attributes, 'SandboxProduct') !== false;
            })->count() > 0;
        $productGroups = $app->products->groupBy('name')->toArray();
        $products = $products->pluck('attributes', 'name');
        $countriesByCode = Country::pluck('iso', 'code');
        $apigeeAttributes = ApigeeService::getApigeeAppAttributes($app);
        $appAttributes = array_merge($apigeeAttributes, $app->attributes);
        $originalProducts = $credentials['apiProducts'];
        $sandboxProducts = [];

        if ($hasSandboxProducts) {
            $credentialsType = 'consumerKey-sandbox';

            foreach ($products as $name => $attributes) {
                $attr = json_decode($attributes, true);
                $newProducts[] = $attr['SandboxProduct'] ?? $name;
            }
        } else {
            $credentialsType = 'consumerKey-production';

            foreach ($products as $name => $attributes) {
                $attr = json_decode($attributes, true);
                $newProducts[] = $attr['ProductionProduct'] ?? $name;
                if (isset($attr['SandboxProduct'])) {
                    $sandboxProducts[$attr['SandboxProduct']] = ['status' => $productGroups[$attr['SandboxProduct']][0]['pivot']['status'], 'actioned_at' => $productGroups[$attr['SandboxProduct']][0]['pivot']['actioned_at']];
                }
            }
        }

        $key = $this->getCredentials($app, $credentialsType, 'string');

        if (empty($key)) {
            $reasonMsg = 'Could not find the Consumer Key. Please contact us if this happens again';

            if ($request->ajax()) {
                return response()->json(['message' => $reasonMsg], 424);
            }

            return redirect()->route('app.index')->with('alert', "error:{$reasonMsg}");
        }

        $appAttributes = array_merge($appAttributes, [
            'DisplayName' => $validated['display_name'] ?? $app->display_name,
            'Description' => $validated['description'],
            'Country' => $validated['country'],
            'location' => $countriesByCode[$validated['country']] ?? "",
            'EntityName' => $validated['entity_name'] ?? "",
            'Channels' =>  $channels,
            'ContactNumber' => $validated['contact_number'] ?? "",
        ]);

        $data = [
            'name' => $app->name,
            'key' => $key,
            'apiProducts' => $newProducts,
            'originalProducts' => $originalProducts,
            'keyExpiresIn' => -1,
            'attributes' => ApigeeService::formatToApigeeAttributes($appAttributes),
            'callbackUrl' => $validated['url'] ?? '',
        ];

        $updatedResponse = ApigeeService::updateApp($data, $appTeam);

        if ($updatedResponse->failed()) {
            $reasonMsg = $updatedResponse['message'] ?? 'There was a problem updating your app. Please try again later.';

            if ($request->ajax()) {
                return response()->json(['response' => "error:{$reasonMsg}"], $updatedResponse->status());
            }

            return redirect()->back()->with('alert', "error:{$reasonMsg}");
        }

        $updatedResponse = $updatedResponse->json();

        $app->update([
            'display_name' => $appAttributes['DisplayName'],
            'attributes' => $appAttributes,
            'credentials' => $updatedResponse['credentials'],
            'callback_url' => $data['callbackUrl'],
            'description' => $appAttributes['Description'],
            'country_code' => $validated['country'],
            "entity_name" => $validated['entity_name'] ?? "",
            "channels" => $channels ?? "",
            "contact_number" => $validated['contact_number'] ?? "",
        ]);

        // Notification creation on apps update
        if ($app->team) {
            $appUsers = $app->team->users->pluck('id')->toArray();
            if ($previewName !== $app->display_name) {
                foreach ($appUsers as $user) {
                    Notification::create([
                        'user_id' => $user,
                        'notification' => "Your team's App <strong> {$previewName} </strong> has been updated to <strong> {$app->display_name} </strong>. Please navigate to your <a href='/teams'>team</a> for more info",
                    ]);
                }
            } else {
                foreach ($appUsers as $user) {
                    Notification::create([
                        'user_id' => $user,
                        'notification' => "Your team's App <strong> {$app->display_name} </strong> has been updated. Please navigate to your <a href='/teams'>team</a> for more info",
                    ]);
                }
            }
        }

        if ($app->developer) {
            Notification::create([
                'user_id' => $app->developer->id,
                'notification' => "Your App <strong> {$app->display_name} </strong> has been updated please navigate to your <a href='/apps'>apps</a> to view the changes",
            ]);
        }

        $app->products()->sync(
            array_reduce(
                ApigeeService::getLatestCredentials($updatedResponse['credentials'])['apiProducts'],
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

        if ($sandboxProducts) {
            $app->products()->attach($sandboxProducts);
        }

        $opcoUserEmails = $app->country->opcoUser->pluck('email')->toArray();
        if (empty($opcoUserEmails)) {
            $opcoUserEmails = config('mail.mail_to_address');
        }
        Mail::to($opcoUserEmails)->send(new UpdateApp($app));

        if ($request->ajax()) {
            return response()->json(['response' => $updatedResponse]);
        }

        return redirect(route('app.index'));
    }

    public function destroy($app, DeleteAppRequest $request)
    {
        $user = auth()->user();
        $userTeams = $user->teams()->pluck('id')->toArray();

        $app = App::where('slug', $app)->where(
            fn($q) => $q->where('developer_id', auth()->user()->developer_id)
                ->orWhereIn('team_id', $userTeams)
        )->firstOrFail();

        if ($app->team_id && $app->team_id !== null) {
            $appUsers = $app->team->users->pluck('id')->toArray();
            $team = $app->team->name;

            if ($appUsers) {
                foreach ($appUsers as $user) {
                    Notification::create([
                        'user_id' => $user,
                        'notification' => "An app <strong> {$app->display_name} </strong> from your team <strong> {$team} </strong>  has been deleted.",
                    ]);
                }
            }
        }

        $validated = $request->validated();
        $developerEmail = $user->email ?? $app->team->email;

        ApigeeService::delete("developers/{$developerEmail}/apps/{$validated['name']}");
        $app->delete();

        return redirect(route('app.index'));
    }

    /**
     * @param App $app
     * @param Request $request
     * @return JsonResponse|void
     */
    public function saveCustomAttributeFromApigee(App $app, Request $request)
    {
        try {
            // Fetch attributes from Apigee
            $apigeeAttributes = ApigeeService::getApigeeAppAttributes($app);

            // Format the Apigee attributes
            $attrs = ApigeeService::formatToApigeeAttributes($apigeeAttributes);
            $formattedAttributes = ApigeeService::formatAppAttributes($attrs);

            // Retrieve existing attributes from the app, decode if necessary
            $existingAttributes = is_array($app->attributes)
                ? $app->attributes
                : json_decode($app->attributes, true) ?? [];

            // Normalize keys: case-sensitive reference keys you want to preserve
            $referenceKeys = [
                'Country', 'TeamName', 'location', 'Description', 'DisplayName',
                'AutoRenewAllowed', 'PermittedSenderIDs', 'senderMsisdn',
                'PermittedPlanIDs', 'originalChannelID', 'partnerName',
                'Channels', 'EntityName', 'ContactNumber', 'Notes'
            ];

            // Normalize formatted attributes
            $normalizedAttributes = [];
            foreach ($formattedAttributes as $key => $value) {
                $normalizedKey = array_search(strtolower($key), array_map('strtolower', $referenceKeys));
                $normalizedAttributes[$normalizedKey !== false ? $referenceKeys[$normalizedKey] : $key] = $value;
            }

            // Normalize existing attributes
            $normalizedExistingAttributes = [];
            foreach ($existingAttributes as $key => $value) {
                $normalizedKey = array_search(strtolower($key), array_map('strtolower', $referenceKeys));
                $normalizedExistingAttributes[$normalizedKey !== false ? $referenceKeys[$normalizedKey] : $key] = $value;
            }

            // Merge normalized attributes
            $mergedAttributes = array_merge($normalizedExistingAttributes, $normalizedAttributes);

            // Update the app's attributes and save
            $app->attributes = $mergedAttributes;
            $app->save();

            return response()->json([
                'success' => true,
                'message' => 'Attributes fetched from Apigee successfully, normalized, and merged.',
            ], 200);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
                'error_code' => 500
            ], 500);
        }
    }


    /**
     * Gets the credentials.
     *
     * @param \App\App $app The application
     * @param string $type The type
     *
     * @return     string|\Illuminate\Http\JsonResponse  The credentials.
     */
    public function getCredentials(App $app, $type, $respondWith = 'jsonResponse')
    {
        return ApigeeService::getCredentials($app, $type, $respondWith);
    }

    /**
     * Request to renew an apps credentials
     *
     * @param \App\App $app The application
     * @param string $type The type
     *
     * @return     \Illuminate\Http\RedirectResponse  Redirect to the Dashboard
     */
    public function requestRenewCredentials(App $app, string $type)
    {
        if ($app->team) {
            Mail::to($app->team->email)->send(new CredentialRenew($app, $type));
        } else {
            Mail::to(auth()->user())->send(new CredentialRenew($app, $type));
        }

        return redirect()->route('app.index')->with('alert', 'success:You will receive an email to renew your apps credentials');
    }

    /**
     * Renew an apps credentials
     *
     * @param \App\App $app The application
     * @param string $type The type
     *
     * @return     \Illuminate\Http\RedirectResponse  Redirect to the Dashboard
     */
    public function renewCredentials(App $app, string $type)
    {
        $credentialsType = 'consumerKey-' . $type;
        $consumerKey = $this->getCredentials($app, $credentialsType, 'string');

        $entity = $app->getEntity();

        $updatedApp = ApigeeService::renewCredentials($entity, $app, $consumerKey);

        if ($updatedApp->failed()) {
            $reasonMsg = $updatedApp['message'] ?? 'There was a problem renewing the credentials. Please try again later.';

            return redirect()->route('app.index')->with('alert', "error:{$reasonMsg}");
        }

        $app->update([
            'credentials' => $updatedApp['credentials']
        ]);

        return redirect()->route('app.index')->with('alert', 'success:Your credentials have been renewed');
    }

    /**
     * Go live process
     *
     * @param \App\App $app The application
     * @param \App\Services\Kyc\KycService $kycService The kyc service
     *
     * @return     \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse  Redirect to the correct kyc form
     */
    public function goLive(App $app, KycService $kycService)
    {
        $app->load(['products', 'country']);
        $goLiveProducts = $app->products();
        $productionProducts = $goLiveProducts->get()->map(fn($prod) => $prod->attributes['ProductionProduct'] ?? '');
        if ($productionProducts->count() > 0) {
            $productionProducts = Product::findMany($productionProducts);
        }
        $groups = [...$goLiveProducts->pluck('group')->toArray(), ...$productionProducts->pluck('group')->toArray()];
        $kyc = $kycService->getNextKyc($groups);

        if (is_null($kyc)) {
            $resp = $this->addNewCredentials($app);

            if (!$resp['success']) {
                return redirect()->back()->with('alert', "error:{$resp['message']}");
            }

            $app->update([
                'live_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->back()->with('alert', "success:{$app->display_name} is now live.");
        }

        $kycResources = $kyc->resources($app);

        return redirect($kycResources['route']);
    }

    /**
     * Show the kyc form
     *
     * @param \App\App $app The application
     * @param string $group The group
     * @param \App\Services\Kyc\KycService $kycService The kyc service
     *
     * @return     \Illuminate\View\View|\Illuminate\Contracts\View\Factory  Show the form
     */
    public function kyc(App $app, $group, KycService $kycService)
    {
        $app->load('country');
        $kyc = $kycService->load($group);
        $kycResources = $kyc->resources($app);

        return view($kycResources['view'])->with($kycResources['with']);
    }

    /**
     * Direct to the store mechanism for this kyc
     *
     * @param \App\App $app The application
     * @param string $group The group
     * @param \App\Http\Requests\KycRequest $request The request
     *
     * @return     method                         The method to store the form details
     */
    public function kycStore(App $app, $group, KycRequest $request)
    {
        $kycStoreMethod = "{$group}KycStore";
        return $this->$kycStoreMethod($app, $group, $request);
    }

    /**
     * The MoMo kyc store method
     *
     * @param \App\App $app The application
     * @param string $group The group
     * @param \App\Http\Requests\KycRequest $request The request
     *
     * @return     \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse  Redirect to app index
     */
    protected function momoKycStore($app, $group, KycRequest $request)
    {
        $data = $request->validated();

        $app->load([
            'products',
            'country' => fn($query) => $query->with('opcoUser')
        ]);

        $files = $request->file('files');
        $data['files'] = [];
        $data['app'] = $app;
        $data['group'] = $group;

        if (!isset($files[$data['business_type']])) {
            return back()->withErrors('Please add all the files requested.')->withInput();
        }

        $fileName = 'signed-contracting-requirements.pdf';
        $files['Signed Contracting Requirements']->storeAs("kyc/{$app->aid}", $fileName, 'local');
        $data['files'][] = "kyc/{$app->aid}/$fileName";

        foreach ($files[$data['business_type']] as $name => $file) {
            if (!$file) {
                return back()->withErrors('Please add all the files requested.')->withInput();
            }

            $fileName = Str::slug(substr($name, 0, 32)) . '.pdf';
            $file->storeAs("kyc/{$app->aid}", $fileName, 'local');
            $data['files'][] = "kyc/{$app->aid}/$fileName";
        }

        $resp = $this->addNewCredentials($app);

        if (!$resp['success']) {
            return redirect()->back()->with('alert', "error:{$resp['message']}");
        }

        $app->update([
            'live_at' => date('Y-m-d H:i:s'),
            'kyc_status' => "Documents Received"
        ]);

        $opcoUserEmails = $app->country->opcoUser->pluck('email')->toArray();
        if (empty($opcoUserEmails)) {
            $opcoUserEmails = config('mail.mail_to_address');
        }
        Mail::to($opcoUserEmails)->send(new GoLiveMail($data));

        return redirect()->route('app.index')->with('alert', 'info:Your app is in review;You will get an email about the progress.');
    }

    /**
     * Adds new credentials.
     *
     * @param \App\App $app The application
     *
     * @return     array     The response shows if successful or not and has response data
     */
    protected function addNewCredentials(App $app): array
    {
        $apiProductsApigee = [];
        $apiProductsPortal = [];

        foreach ($app->products as $product) {
            $apiProductsPortal[$product->name] = ['status' => $product->pivot->status, 'actioned_at' => $product->pivot->actioned_at];

            // ProductionProduct is set in the SyncProducts Console Command.
            // This checks if there is a relationship from a sandbox/staging product to a production product.
            if (isset($product->attributes['ProductionProduct'])) {
                $apiProductsPortal[$product->attributes['ProductionProduct']] = ['status' => $product->pivot->status, 'actioned_at' => $product->pivot->actioned_at];
                $apiProductsApigee[] = $product->attributes['ProductionProduct'];
                continue;
            }

            $apiProductsApigee[] = $product->name;
        }

        $data = [
            'name' => $app['name'],
            'apiProducts' => $apiProductsApigee,
            'callbackUrl' => $app['callback_url'],
            'attributes' => [
                [
                    'name' => 'DisplayName',
                    'value' => $app['display_name'],
                ],
                [
                    'name' => 'Description',
                    'value' => $app['description'],
                ],
                [
                    'name' => 'Country',
                    'value' => $app->country->code,
                ],
                [
                    'name' => 'location',
                    'value' => $app->country->iso,
                ],
                [
                    'name' => 'ApprovedAt',
                    'value' => date('Y-m-d H:i:s'),
                ],
            ]
        ];

        $resp = ApigeeService::updateAppWithNewCredentials($data);

        if ($resp->failed()) {
            $reasonMsg = $resp['message'] ?? 'There was a problem creating your app. Please try again later.';

            return ['success' => false, 'message' => $reasonMsg];
        }

        $resp = $resp->json();

        $app->update([
            'attributes' => ApigeeService::formatAppAttributes($data['attributes']),
            'credentials' => $resp['credentials']
        ]);

        $app->products()->sync($apiProductsPortal);

        return [
            'success' => true,
            'apigeeResp' => $resp,
            'data' => $data
        ];
    }

    /**
     * Note that teams are not  synced to apigee they are created from the portal and synced to Apigee
     * We have to make a call to apigee to get the team/company details in order to create attributes
     * Save custom attributes to Apigee
     * @param App $app
     * @param AppAttributesRequest $request
     * @return JsonResponse|RedirectResponse
     */
    public function saveAppCustomAttributeFromApigee(App $app, AppAttributesRequest $request)
    {
        // Validate the incoming request
        $validated = $request->validated();
        $newAttributes = $validated['attribute'];

        // Validate attribute keys
        foreach ($newAttributes as $key => $value) {
            if (is_numeric($key) && !preg_match('/[a-zA-Z]/', $key)) {
                return response()->json([
                    'success' => false,
                    'message' => "The name value '{$key}' cannot be a numeric value only.",
                ], 400);
            }
        }

        // Ensure existing attributes are an array or initialize as an empty array
        $existingAttributes = is_array($app->attributes)
            ? $app->attributes
            : json_decode($app->attributes, true) ?? [];

        // Normalize and merge attributes
        $referenceKeys = [
            'Country', 'TeamName', 'location', 'Description', 'DisplayName',
            'AutoRenewAllowed', 'PermittedSenderIDs', 'senderMsisdn',
            'PermittedPlanIDs', 'originalChannelID', 'partnerName',
            'Channels', 'EntityName', 'ContactNumber', 'Notes'
        ];

        $normalizedAttributes = $this->normalizeAttributes($existingAttributes, $referenceKeys);
        $normalizedNewAttributes = $this->normalizeAttributes($newAttributes, $referenceKeys);
        $mergedAttributes = array_merge($normalizedAttributes, $normalizedNewAttributes);

        $apigeeAttributes = ApigeeService::formatToApigeeAttributes($mergedAttributes);

        if (count($apigeeAttributes) > 18) {
            return response()->json([
                'success' => false,
                'message' => 'Attributes array cannot exceed 18 properties.'
            ], 400);
        }

        // Get developer or team details
        $team = $app->team;
        $developer = $app->developer;


        if ($team) {
            $teamName = strtolower($team->username);
            $getCompany = ApigeeService::get("companies/{$teamName}"); // Get company details from APIGEE

            if (isset($getCompany['code'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'The Team does not exist in Apigee.',
                ], 400);
            }

            $companyName = strtolower($getCompany['name']);
            $accessUrl = "companies/{$companyName}";
        } elseif ($developer) {
            $developerEmail = $developer->email;
            $accessUrl = "developers/{$developerEmail}";
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Developer or Team/Company does not exists in Apigee.',
            ], 400);
        }

        // Send updated attributes to Apigee
        $apigeeResponse = ApigeeService::put("{$accessUrl}/apps/{$app->name}", [
            'name' => $app->name,
            'attributes' => $apigeeAttributes,
        ]);

        if ($apigeeResponse->status() === 200) {
            $app->update(['attributes' => $mergedAttributes]);

            return response()->json([
                'success' => true,
                'message' => 'Attributes successfully saved.',
                'attributes' => $mergedAttributes,
            ]);
        }

        Log::error('Apigee response error', [
            'response' => $apigeeResponse,
            'url' => "{$accessUrl}/apps/{$app->name}",
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to update attributes in Apigee.',
        ], 500);
    }

    /**
     * Normalize attributes
     * @param array $attributes
     * @param array $referenceKeys
     * @return array
     */
    private function normalizeAttributes(array $attributes, array $referenceKeys)
    {
        $normalized = [];
        foreach ($attributes as $key => $value) {
            $normalizedKey = array_search(strtolower($key), array_map('strtolower', $referenceKeys));
            $normalized[$normalizedKey !== false ? $referenceKeys[$normalizedKey] : $key] = $value;
        }
        return $normalized;
    }

    /**
     * Update custom attributes
     * @param App $app
     * @param AppAttributesRequest $request
     * @return JsonResponse
     */
    public function updateCustomAttributes(App $app, AppAttributesRequest $request)
    {
        // Validate the form data
        $validated = $request->validated();
        $newAttributes = $validated['attribute'];

        // Retrieve existing attributes from the app, decode if necessary
        $existingAttributes = is_array($app->attributes)
            ? $app->attributes
            : json_decode($app->attributes, true) ?? [];

        // Loop through new attributes to update them dynamically
        foreach ($newAttributes as $oldKey => $newValue) {
            // Check if the oldKey is an existing key
            if (array_key_exists($oldKey, $existingAttributes)) {
                // Update the value of the existing key
                $existingAttributes[$oldKey] = $newValue;
            } else {
                // Rename the key if the oldKey is different from newKey
                foreach ($existingAttributes as $key => $value) {
                    if ($value === $newValue) {
                        // If the value already exists, set it with the new key
                        $existingAttributes[$oldKey] = $existingAttributes[$key]; // Add with old key
                        unset($existingAttributes[$key]); // Remove the old key
                        break;
                    }
                }
            }
        }

        // Prepare attributes for Apigee by flattening them
        $flattenedAttributes = $this->flattenAttributes($existingAttributes);

        // Convert flattened attributes back to the structure needed for Apigee
        $apigeeAttributes = ApigeeService::formatToApigeeAttributes($existingAttributes);

        // Get the developer or team details
        $team = $app->team;
        $developer = $app->developer;

        if (!$developer) {
            return response()->json([
                'success' => false,
                'message' => 'Developer information is missing.',
            ], 400);
        }

        // Construct the API access URL for Apigee
        $developerEmail = $developer->email;
        $accessUrl = $team ? "companies/{$team->username}" : "developers/{$developerEmail}";

        // Send updated attributes to Apigee
        $apigeeResponse = ApigeeService::put("{$accessUrl}/apps/{$app->name}", [
            'name' => $app->name,
            'attributes' => $apigeeAttributes,
        ]);

        // Check if the response contains the 'success' key
        if ($apigeeResponse->status() === 200) {
            // Update the local database with the edited attributes
            $app->update(['attributes' => $existingAttributes]);

            return response()->json([
                'success' => true,
                'message' => 'Attributes successfully updated.',
                'attributes' => $flattenedAttributes, // Return flattened attributes
            ]);
        }

        // Log the error response for debugging
        Log::error('Apigee response error', [
            'response' => $apigeeResponse,
            'url' => "{$accessUrl}/apps/{$app->name}",
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to update attributes in Apigee.',
        ], 500);
    }

    /**
     * Flatten attributes to Apigee
     * @param array $attributes
     * @return array
     */
    private function flattenAttributes(array $attributes): array
    {
        $flattened = [];

        foreach ($attributes as $key => $value) {
            // If value is a nested array, handle it
            if (is_array($value) && isset($value[0])) {
                foreach ($value[0] as $innerKey => $innerValue) {
                    // Add only the key-value pair without the type (e.g. "string")
                    $flattened[$innerKey] = $innerValue;
                }
            } else {
                // Handle simple key-value pairs like {"Country": "za"}
                $flattened[$key] = $value;
            }
        }

        return $flattened;
    }

    /**
     * @param $attributes
     * @return array
     */
    protected function formatForApigee($attributes): array
    {
        $formattedAttributes = [];

        if (is_array($attributes)) {
            foreach ($attributes as $type => $items) {
                if (is_array($items)) {
                    foreach ($items as $item) {
                        if (is_array($item)) {
                            $formattedAttributes = array_merge($formattedAttributes, $item);
                        }
                    }
                }
            }
        }
        return $formattedAttributes;
    }

    /**
     * Get custom attributes list
     * @param App $app
     * @return JsonResponse
     */
    public function getCustomAttributes(App $app)
    {
        // Check if attributes exist
        if (!$app->attributes) {
            return response()->json([
                'success' => false,
                'message' => 'No attributes found for the app.'
            ], 404);
        }

        // Ensure attributes are decoded or returned as array
        $attributes = is_array($app->attributes)
            ? $app->attributes
            : json_decode($app->attributes, true) ?? [];

        // Flatten attributes by ignoring the type (like "string")
        $flattenedAttributes = $this->flattenAttributes($attributes);

        // Example: Count of attributes
        $attributeCount = count($flattenedAttributes);

        return response()->json([
            'success' => true,
            'attributes' => $flattenedAttributes, // Send back flattened attributes
            'count' => $attributeCount, // Include total count of attributes
        ]);
    }

    /**
     * Delete custom attribute from Apigee
     * @param App $app
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteCustomAttribute(App $app, Request $request)
    {
        // Get the attribute key to be deleted from the request
        $attributeKey = $request->input('attribute_key');

        // Retrieve existing attributes from the app, decode if necessary
        $existingAttributes = is_array($app->attributes)
            ? $app->attributes
            : json_decode($app->attributes, true) ?? [];

        // Check if the attribute exists in the existing attributes
        if (array_key_exists($attributeKey, $existingAttributes)) {
            // Remove the attribute
            unset($existingAttributes[$attributeKey]);

            // Prepare attributes for Apigee by flattening them
            $flattenedAttributes = $this->flattenAttributes($existingAttributes);

            // Convert flattened attributes back to the structure needed for Apigee
            $apigeeAttributes = ApigeeService::formatToApigeeAttributes($existingAttributes);

            // Get the developer or team details
            $team = $app->team;
            $developer = $app->developer;

            if (!$developer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Developer information is missing.',
                ], 400);
            }

            // Construct the API access URL for Apigee
            $developerEmail = $developer->email;
            $accessUrl = $team ? "companies/{$team->username}" : "developers/{$developerEmail}";

            // Send updated attributes to Apigee
            $apigeeResponse = ApigeeService::put("{$accessUrl}/apps/{$app->name}", [
                'name' => $app->name,
                'attributes' => $apigeeAttributes,
            ]);

            if ($apigeeResponse->status()) {
                // Update the local database with the updated attributes
                $app->update(['attributes' => $existingAttributes]);

                return response()->json([
                    'success' => true,
                    'message' => 'Attribute successfully deleted.',
                    'attributes' => $flattenedAttributes, // Return flattened attributes
                ]);
            }

            // Log the error response for debugging
            Log::error('Apigee response error', [
                'response' => $apigeeResponse,
                'url' => "{$accessUrl}/apps/{$app->name}",
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update attributes in Apigee.',
            ], 500);
        }

        return response()->json([
            'success' => false,
            'message' => 'Attribute does not exist.',
        ], 404);
    }
}
