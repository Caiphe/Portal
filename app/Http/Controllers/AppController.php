<?php

namespace App\Http\Controllers;

use App\App;
use App\Product;
use App\Country;
use App\Http\Requests\CreateAppRequest;
use App\Http\Requests\DeleteAppRequest;
use App\Http\Requests\KycRequest;
use App\Mail\CredentialRenew;
use App\Mail\GoLiveMail;
use App\Mail\NewApp;
use App\Mail\UpdateApp;
use App\Services\ApigeeService;
use App\Services\Kyc\KycService;
use App\Services\ProductLocationService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AppController extends Controller
{
    public function index()
    {
        $apps = App::with(['products.countries', 'country', 'developer'])
            ->byUserEmail(\Auth::user()->email)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('status');

        return view('templates.apps.index', [
            'approvedApps' => $apps['approved'] ?? [],
            'revokedApps' => $apps['revoked'] ?? [],
        ]);
    }

    public function create(ProductLocationService $productLocationService)
    {
        [$products, $countries] = $productLocationService->fetch();

        return view('templates.apps.create', [
            'products' => $products,
            'productCategories' => array_keys($products->toArray()),
            'countries' => $countries ?? '',
        ]);
    }

    public function store(CreateAppRequest $request)
    {
        $validated = $request->validated();
        $countriesByCode = Country::pluck('iso', 'code');
        $products = Product::whereIn('name', $request->products)->pluck('attributes', 'name');
        $productIds = [];
        $attr = [];
        foreach ($products as $name => $attributes) {
            $attr = json_decode($attributes, true);
            $productIds[] = $attr['SandboxProduct'] ?? $name;
        }

        $data = [
            'name' => Str::slug($validated['display_name']),
            'apiProducts' => $productIds,
            'keyExpiresIn' => -1,
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
                    'value' => $countriesByCode[$validated['country']] ?? "",
                ],
            ],
            'callbackUrl' => $validated['url'],
        ];

        $createdResponse = ApigeeService::createApp($data);

        if (strpos($createdResponse, 'duplicate key') !== false) {
            return response()->json(['success' => false, 'message' => 'There is already an app with that name.'], 409);
        }

        $attributes = ApigeeService::getAppAttributes($createdResponse['attributes']);

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

        $opcoUserEmails = $app->country->opcoUser->pluck('email')->toArray();
        if (empty($opcoUserEmails)) {
            $opcoUserEmails = env('MAIL_TO_ADDRESS');
        }
        Mail::to($opcoUserEmails)->send(new NewApp($app));

        if ($request->ajax()) {
            return response()->json(['response' => $createdResponse]);
        }

        return redirect(route('app.index'));
    }

    public function edit($developerId, App $app)
    {
        $products = Product::with('category')
            ->where('category_cid', '!=', 'misc')
            ->where(fn ($q) => $q->basedOnUser(auth()->user())->orWhereIn('pid', $app->products->pluck('pid')->toArray()))
            ->get();

        $countryCodes = $products->pluck('locations')->implode(',');
        $countries = Country::whereIn('code', explode(',', $countryCodes))->pluck('name', 'code');

        $products = $products->sortBy('category.title')->groupBy('category.title');

        $app->load('products', 'country');
        $credentials = $app->credentials;
        $selectedProducts = end($credentials)['apiProducts'] ?? [];

        if (count($credentials) === 1 && $app->products->filter(fn ($prod) => isset($prod->attributes['ProductionProduct']))->count() > 0) {
            $selectedProducts = $app->products->map(fn ($prod) => $prod->attributes['ProductionProduct'] ?? $prod->name)->toArray();
        }

        return view('templates.apps.edit', [
            'products' => $products,
            'countries' => $countries ?? '',
            'data' => $app,
            'selectedProducts' => $selectedProducts,
        ]);
    }

    public function update($developerId, App $app, CreateAppRequest $request)
    {
        $validated = $request->validated();
        $app->load('products');
        $credentials = $app->credentials;
        $sandboxProducts = $app->products->filter(function ($prod) {
            $envArr = explode(',', $prod->environments);
            return in_array('sandbox', $envArr) && !in_array('prod', $envArr);
        });
        $hasSandboxProducts = $sandboxProducts->count() > 0;
        $products = Product::whereIn('name', $validated['products'])->pluck('attributes', 'name');

        if (count($credentials) === 1 && $hasSandboxProducts) {
            $credentialsType = 'consumerKey-sandbox';
            $originalProducts = $credentials[0]['apiProducts'];
            foreach ($products as $name => $attributes) {
                $attr = json_decode($attributes, true);
                $newProducts[] = $attr['SandboxProduct'] ?? $name;
            }
            $syncProducts = $newProducts;
        } else {
            $credentialsType = 'consumerKey-production';
            $originalProducts = end($credentials)['apiProducts'];

            foreach ($products as $name => $attributes) {
                $attr = json_decode($attributes, true);
                $newProducts[] = $attr['ProductionProduct'] ?? $name;
            }

            if ($hasSandboxProducts) {
                $syncProducts = [...$newProducts, ...$credentials[0]['apiProducts']];
            } else {
                $syncProducts = $newProducts;
            }
        }

        $key = $this->getCredentials($app, $credentialsType, 'string');

        if (empty($key)) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Could not find the Consumer Key. Please contact us if this happens again'], 500);
            }

            return redirect()->route('app.index')->with('alert', 'error:Could not find the Consumer Key. Please contact us if this happens again');
        }

        $data = [
            'name' => $app->name,
            'key' => $key,
            'apiProducts' => $newProducts,
            'originalProducts' => $originalProducts,
            'keyExpiresIn' => -1,
            'attributes' => [
                [
                    'name' => 'DisplayName',
                    'value' => $validated['display_name'] ?? $app->display_name,
                ],
                [
                    'name' => 'Description',
                    'value' => $validated['description'],
                ],
                [
                    'name' => 'Country',
                    'value' => $validated['country'],
                ],
            ],
            'callbackUrl' => $validated['url'] ?? '',
        ];

        $updatedResponse = ApigeeService::updateApp($data)->json();

        $attributes = ApigeeService::getAppAttributes($data['attributes']);

        $app->update([
            'display_name' => $data['attributes'][0]['value'],
            'attributes' => $attributes,
            'credentials' => $updatedResponse['credentials'],
            'callback_url' => $data['callbackUrl'],
            'description' => $data['attributes'][1]['value'],
            'country_code' => $validated['country'],
        ]);

        $app->products()->sync($syncProducts);

        $opcoUserEmails = $app->country->opcoUser->pluck('email')->toArray();
        if (empty($opcoUserEmails)) {
            $opcoUserEmails = env('MAIL_TO_ADDRESS');
        }
        Mail::to($opcoUserEmails)->send(new UpdateApp($app));

        if ($request->ajax()) {
            return response()->json(['response' => $updatedResponse]);
        }

        return redirect(route('app.index'));
    }

    public function destroy($developerId, App $app, DeleteAppRequest $request)
    {
        $validated = $request->validated();

        $user = \Auth::user();
        ApigeeService::delete("developers/{$user->email}/apps/{$validated['name']}");

        $app->delete();

        return redirect(route('app.index'));
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
        Mail::to(auth()->user())->send(new CredentialRenew($app, $type));

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

        $updatedApp = ApigeeService::renewCredentials(auth()->user(), $app, $consumerKey);

        if ($updatedApp->status() !== 200) {
            return redirect()->route('app.index')->with('alert', 'error:Sorry there was an error renewing the credentials');
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
            $opcoUserEmails = env('MAIL_TO_ADDRESS');
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
            $apiProductsPortal[] = $product->name;

            // ProductionProduct is set in the SyncProducts Console Command.
            // This checks if there is a relationship from a sandbox/staging product to a production product.
            if (isset($product->attributes['ProductionProduct'])) {
                $apiProductsPortal[] = $product->attributes['ProductionProduct'];
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
        $status = $resp->status();
        $resp = $resp->json();
        if ($status !== 200 && $status !== 201) {
            return [
                'success' => false,
                'message' => $resp['message']
            ];
        }

        $app->update([
            'attributes' => $data['attributes'],
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
