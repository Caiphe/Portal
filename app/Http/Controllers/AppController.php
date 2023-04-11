<?php

namespace App\Http\Controllers;

use DB;
use App\App;
use App\Team;
use App\User;
use App\Country;
use App\Product;
use Carbon\Carbon;
use App\Mail\NewApp;
use App\Mail\UpdateApp;
use App\Mail\GoLiveMail;
use Illuminate\Support\Str;
use App\Mail\TeamAppCreated;
use Illuminate\Http\Request;
use App\Mail\CredentialRenew;
use App\Services\ApigeeService;
use App\Services\Kyc\KycService;
use App\Http\Requests\KycRequest;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CreateAppRequest;
use App\Http\Requests\DeleteAppRequest;
use App\Http\Requests\CustomAttributesRequest;

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
            ->when($userTeams, fn ($q) => $q->orWhereIn('team_id', $userTeams->pluck('id')))
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
            ->when($request->has('product'), function($q) use(&$product, $product_sanitized){
                $product = Product::with(['countries'])->where('slug', $product_sanitized)->first();
                abort_if($product === null, 404);
                $productLocations = $product->countries->pluck('code')->toArray();
                $q->byLocations($productLocations);
            })
            ->get()
            ->merge($assignedProducts);

        $locations = $product->locations ?? $products->pluck('locations')->implode(',');
        $locations = array_unique(explode(',', $locations));
        $countries = Country::whereIn('code', $locations)->pluck('name', 'code');
        $products = $products->sortBy('display_name')->groupBy('category.title')->sortKeys();

        return view('templates.apps.create', [
            'productSelected' => $product,
            'products' => $products,
            'productCategories' => array_keys($products->toArray()),
            'teams' => $userOwnTeams,
            'countries' => $countries ?? '',
            'user' => $user
        ]);
    }

    public function store(CreateAppRequest $request)
    {
        $user = $request->user();
        $count = App::where('developer_id', auth()->user()->developer_id)
                ->where('created_at', '>=', Carbon::now()->startOfDay())
                ->count();
        
        $userRoles = array_unique(explode(',', $user->getRolesListAttribute()));

        if($count >= 5 && !in_array('Admin', $userRoles)){
            abort('429');
        }

        $validated = $request->validated();
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
        ]);

        if ($team) {
            event(new TeamAppCreated($team));
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
            fn ($q) => $q->where('developer_id', auth()->user()->developer_id)
                ->orWhereIn('team_id', $userTeams)
        )->firstOrFail();
        $products = Product::with('category')
            ->where('category_cid', '!=', 'misc')
            ->where(fn ($q) => $q->basedOnUser(auth()->user())->orWhereIn('pid', $app->products->where('environments', '!=', 'sandbox')->pluck('pid')->toArray()))
            ->get();
        $assignedProducts = $user->assignedProducts()->with('category')->get();

        $countryCodes = $products->pluck('locations')->implode(',');
        $countries = Country::whereIn('code', explode(',', $countryCodes))->pluck('name', 'code');

        $products = $products->merge($assignedProducts)->sortBy('category.title')->groupBy('category.title');

        $app->load('products', 'country');
        $credentials = $app->credentials;
        $selectedProducts = ApigeeService::getLatestCredentials($credentials)['apiProducts'] ?? [];

        if (count($credentials) === 1 && $app->products->filter(fn ($prod) => isset($prod->attributes['ProductionProduct']))->count() > 0) {
            $selectedProducts = $app->products->map(fn ($prod) => $prod->attributes['ProductionProduct'] ?? $prod->name)->toArray();
        }

        return view('templates.apps.edit', [
            'products' => $products,
            'countries' => $countries ?? '',
            'data' => $app,
            'selectedProducts' => $selectedProducts,
            'user' => $user
        ]);
    }

    public function update($app, CreateAppRequest $request)
    {
        $user = auth()->user();
        $userTeams = $user->teams()->pluck('id')->toArray();
        $app = App::where('slug', $app)->where(
            fn ($q) => $q->where('developer_id', $user->developer_id)
                ->orWhereIn('team_id', $userTeams)
        )->firstOrFail();
        $validated = $request->validated();
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
            'location' => $countriesByCode[$validated['country']] ?? ""
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
        ]);

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
            fn ($q) => $q->where('developer_id', $user->developer_id)
                ->orWhereIn('team_id', $userTeams)
        )->firstOrFail();
        $validated = $request->validated();

        ApigeeService::delete("developers/{$user->email}/apps/{$validated['name']}");

        $app->delete();

        return redirect(route('app.index'));
    }

    public function saveCustomAttributeFromApigee(App $app, Request $request)
    {
        $apigeeAttributes = ApigeeService::getApigeeAppAttributes($app);
        $attrs= ApigeeService::formatToApigeeAttributes($apigeeAttributes);
        $attributes = ApigeeService::formatAppAttributes($attrs);

        $app->attributes = $attributes;
        $app->save();

        if ($request->ajax()) {
            return response()->json([
                'id' => $app->aid,
                'formHtml' => view('partials.custom-attributes.form', ['app' => $app])->render(),
                'listHtml' => view('partials.custom-attributes.list', ['app' => $app])->render()
            ]);
        }
    }

    public function updateCustomAttributes(App $app, CustomAttributesRequest $request)
    {
        $validated = $request->validated();
        $attributes = ApigeeService::formatAppAttributes($validated['attribute']);
        $appAttributes = $app->attributes;

        $previousCustomAttributes = $app->filterCustomAttributes($appAttributes);
        $appAttributes = array_diff($appAttributes, $previousCustomAttributes);
        $appAttributes = array_merge($appAttributes, $app->filterCustomAttributes($attributes));

        $team = $app->team ?? null;
        $developerEmail = $app->developer->email;
        $accessUrl = $team ? "companies/{$team->username}" : "developers/{$developerEmail}";

        $updatedResponse = ApigeeService::put("{$accessUrl}/apps/{$app->name}", [
            "name" => $app->name,
            'attributes' => ApigeeService::formatToApigeeAttributes($appAttributes),
            "callbackUrl" => $app->url ?? '',
        ]);

        if ($updatedResponse->failed()) {
            $reasonMsg = $updatedResponse['message'] ?? 'There was a problem updating your app. Please try again later.';

            if ($request->ajax()) {
                return response()->json(['response' => "error:{$reasonMsg}"], $updatedResponse->status());
            }

            return redirect()->back()->with('alert', "error:{$reasonMsg}");
        }
        
        $attributes = ApigeeService::formatAppAttributes($updatedResponse['attributes']);

        // Removing extra spaces in any attribute's name
        $attributesWithoutSpaces = array_combine(array_map(function ($key) {
            return str_replace(' ', '', $key);
        }, array_keys($attributes)), $attributes);
        
        $app->update(['attributes' =>  $attributesWithoutSpaces]);

        if ($request->ajax()) {
            return response()->json(['attributes' => $attributesWithoutSpaces]);
        }
    }

    /**
     * Gets the credentials.
     *
     * @param      \App\App                       $app    The application
     * @param      string                         $type   The type
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
     * @param      \App\App                           $app    The application
     * @param      string                             $type   The type
     *
     * @return     \Illuminate\Http\RedirectResponse  Redirect to the Dashboard
     */
    public function requestRenewCredentials(App $app, string $type)
    {
        if($app->team){
            Mail::to($app->team->email)->send(new CredentialRenew($app, $type));
        }else{
            Mail::to(auth()->user())->send(new CredentialRenew($app, $type));
        }

        return redirect()->route('app.index')->with('alert', 'success:You will receive an email to renew your apps credentials');
    }

    /**
     * Renew an apps credentials
     *
     * @param      \App\App                           $app    The application
     * @param      string                             $type   The type
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
     * @param      \App\App                                                          $app         The application
     * @param      \App\Services\Kyc\KycService                                      $kycService  The kyc service
     *
     * @return     \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse  Redirect to the correct kyc form
     */
    public function goLive(App $app, KycService $kycService)
    {
        $app->load(['products', 'country']);
        $goLiveProducts = $app->products();
        $productionProducts = $goLiveProducts->get()->map(fn ($prod) => $prod->attributes['ProductionProduct'] ?? '');
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
     * @param      \App\App                                                  $app         The application
     * @param      string                                                    $group       The group
     * @param      \App\Services\Kyc\KycService                              $kycService  The kyc service
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
     * @param      \App\App                       $app      The application
     * @param      string                         $group    The group
     * @param      \App\Http\Requests\KycRequest  $request  The request
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
     * @param      \App\App                                                          $app      The application
     * @param      string                                                            $group    The group
     * @param      \App\Http\Requests\KycRequest                                     $request  The request
     *
     * @return     \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse  Redirect to app index
     */
    protected function momoKycStore($app, $group, KycRequest $request)
    {
        $data = $request->validated();

        $app->load([
            'products',
            'country' => fn ($query) => $query->with('opcoUser')
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
     * @param      \App\App  $app    The application
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
}
