<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusRequest;
use App\Services\ApigeeService;
use App\App;
use App\Country;
use App\Product;
use App\Mail\KycStatusUpdate;
use App\Mail\ProductAction;
use App\Services\ProductLocationService;
use App\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Mail;
use \Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user()->load(['responsibleCountries']);
        $isAdmin = $user->hasRole('admin');
        $responsibleCountriesCodes = $user->responsibleCountries->pluck('code')->toArray();
        $searchTerm = "%" . $request->get('q', '') . "%";
        $hasSearchTerm = $searchTerm !== '%%';
        $searchCountries = $request->get('countries');
        $hasCountries = $request->has('countries') && !is_null($searchCountries);
        $notAdminNoResponsibleCountries = !$isAdmin && empty($responsibleCountriesCodes);
        $appStatus = $request->get('app-status', 'all');
        $productStatus = $request->get('product-status', 'pending');
        $hasAid = $request->has('aid');
        $previous = url()->previous() ?? null;
        $numberPerPage = (int)$request->get('number_per_page', '15');

        if (($notAdminNoResponsibleCountries) && $request->ajax()) {
            return response()
                ->view('templates.admin.dashboard.data', [
                    'apps' => App::where('country_code', 'none')->paginate($numberPerPage),
                    'countries' => Country::all(),
                ], 200)
                ->header('Vary', 'X-Requested-With')
                ->header('Content-Type', 'text/html');
        } else if ($notAdminNoResponsibleCountries) {
            return view('templates.admin.dashboard.index', [
                'apps' => App::where('country_code', 'none')->paginate($numberPerPage),
                'countries' => Country::all(),
                'previous' => null,
                'selectedCountry' => $request->get('countries', ''),
                'appStatus' => $request->get('app-status', 'pending'),
                'productStatus' => $request->get('product-status', 'pending')
            ]);
        }

        $apps = App::with(['developer', 'team.owner', 'country', 'products' => function ($q) {
            $q->orderByRaw("FIELD (app_product.status, 'pending', 'approved', 'revoked') ASC");
        }, 'products.countries'])
            ->whereNotNull('country_code')
            ->when($hasAid, fn ($q) => $q->where('aid', $request->get('aid')))
            ->when(!$isAdmin, function ($query) use ($responsibleCountriesCodes) {
                $query->whereIn('country_code', $responsibleCountriesCodes);
            })
            ->when($hasSearchTerm, function ($q) use ($searchTerm) {
                $q->where(function ($query) use ($searchTerm) {
                    $query->where('display_name', 'like', $searchTerm)
                        ->orWhere('aid', 'like', $searchTerm)
                        ->orWhereHas('developer', function ($q) use ($searchTerm) {
                            $q->where('first_name', 'like', $searchTerm)
                                ->orWhere('last_name', 'like', $searchTerm)
                                ->orWhere('email', 'like', $searchTerm);
                        });
                });
            })
            ->when($appStatus !== 'all', function ($q) use ($appStatus) {
                $q->where('status', $appStatus);
            })
            ->when($productStatus !== 'all' && !$hasAid, function ($q) use ($productStatus) {
                switch ($productStatus) {
                    case 'pending':
                        $q->whereHas('products', fn ($q) => $q->where('status', 'pending'));
                        break;
                    case 'all-approved':
                        $q->whereDoesntHave('products', function ($q) {
                            $q->where('status', 'revoked');
                        })->whereDoesntHave('products', function ($q) {
                            $q->where('status', 'pending');
                        });
                        break;
                    case 'at-least-one-approved':
                        $q->whereHas('products', fn ($q) => $q->where('status', 'approved'));
                        break;
                    case 'all-revoked':
                        $q->whereDoesntHave('products', function ($q) {
                            $q->where('status', 'approved');
                        })->whereDoesntHave('products', function ($q) {
                            $q->where('status', 'pending');
                        });
                        break;
                    case 'at-least-one-revoked':
                        $q->whereHas('products', fn ($q) => $q->where('status', 'revoked'));
                        break;
                }
            })
            ->when($hasCountries, function ($q) use ($searchCountries) {
                $q->where('country_code', $searchCountries);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate($numberPerPage);

        if ($request->ajax()) {
            return response()
                ->view('templates.admin.dashboard.data', [
                    'apps' => $apps,
                    'countries' => Country::orderBy('name')->pluck('name', 'code'),
                ], 200)
                ->header('Vary', 'X-Requested-With')
                ->header('Content-Type', 'text/html');
        }

        $locations = Product::isPublic()
            ->WhereNotNull('locations')
            ->Where('locations', '!=', 'all')
            ->pluck('locations');

        $locations = array_unique(explode(',', $locations));
        $countries = Country::whereIn('code', array_unique($locations))->orderBy('name')->pluck('name', 'code');

        return view('templates.admin.dashboard.index', [
            'apps' => $apps,
            'countries' => $countries,
            'selectedCountry' => $request->get('countries', ''),
            'appStatus' => $request->get('app-status', 'pending'),
            'productStatus' => $request->get('product-status', 'pending'),
            'previous' => $hasAid && strpos($previous, 'admin/user') !== false ? $previous : null
        ]);
    }

    public function update(UpdateStatusRequest $request)
    {
        $validated = $request->validated();
        $app = App::with(['developer', 'products', 'team'])->find($validated['app']);
        $isTeamApp = !is_null($app->team);
        $product = $app->products()->where('name', $validated['product'])->first();
        $currentUser = $request->user();
        $status = [
            'approve' => 'approved',
            'revoke' => 'revoked',
            'pending' => 'pending'
        ][$validated['action']] ?? 'pending';

        $statusNoteRequest = $request->get('statusNote', 'No note given.') ?: 'No note given';
        $statusNote = $product->pivot->status_note ?? '';
        $statusNote = date('d F Y') . "\n" . ucfirst($status) . " by {$currentUser->full_name}" . "\n\n{$statusNoteRequest}\n\n{$statusNote}";

        $credentials = ApigeeService::getAppCredentials($app);
        $credentials = $validated['for'] === 'staging' ? $credentials[0] : end($credentials);

        $updateId = $isTeamApp ? $app->team->username : $app->developer->email ?? $app->developer_id;

        $response = ApigeeService::updateProductStatus($updateId, $app->name, $credentials['consumerKey'], $validated['product'], $validated['action'], $isTeamApp);
        if ($response->successful()) {
            $app->products()->updateExistingPivot(
                $validated['product'],
                [
                    'status' => $status,
                    'actioned_by' => $currentUser->id,
                    'actioned_at' => date('Y-m-d H:i:s'),
                    'status_note' => $statusNote
                ]
            );
        } else if ($request->ajax()) {
            $body = json_decode($response->body());
            return response()->json(['success' => false, 'body' => $body->message], $response->status());
        } else {
            $body = json_decode($response->body());
            return back()->with('alert', "error:{$body->message}");
        }

        Mail::to(config('mail.mail_to_address'))->send(new ProductAction($app, $validated, $currentUser));

        if ($request->ajax()) {
            $product = $app->products()->where('name', $validated['product'])->first();
            return response()->json(['success' => true, 'body' => $product->notes]);
        }

        return back()->with('alert', 'success:The product has been updated.');
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
        $consumerKey = ApigeeService::getCredentials($app, $credentialsType, 'string');

        $updatedApp = ApigeeService::renewCredentials($app->developer, $app, $consumerKey);

        if ($updatedApp->status() !== 200) {
            return redirect()->route('app.index')->with('alert', 'error:Sorry there was an error renewing the credentials');
        }

        $app->update([
            'credentials' => $updatedApp['credentials']
        ]);

        return redirect()->route('admin.dashboard.index')->with('alert', 'success:Your credentials have been renewed');
    }

    public function updateKycStatus(App $app, Request $request)
    {
        $app->load('developer');
        $data = $request->validate([
            'kyc_status' => 'required'
        ]);

        $app->update([
            'kyc_status' => $data['kyc_status']
        ]);

        Mail::to($app->developer->email ?? $app->team->owner->email)->send(new KycStatusUpdate($app->developer, $app));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'body' => "The KYC status was updated to {$data['kyc_status']}"]);
        }

        return redirect()->back()->with('alert', "success:The KYC status was updated to {$data['kyc_status']}");
    }

    public function updateAppStatus(App $app, Request $request)
    {
        $status = $request->get('status');
        $statusNote = $request->get('status-note', 'No note given.') ?: 'No note given';
        $currentUser = $request->user();
        $attr = $app->attributes;
        $attributes = [];
        $timestamp = date('d F Y');
        if (isset($attr['Notes'])) {
            $attr['Notes'] = $statusNote ? "{$timestamp}\n" . ucfirst($status) . " by {$currentUser->full_name}\n\n{$statusNote}\n\n{$attr['Notes']}" : $attr['Notes'];
        } else if (!empty($statusNote)) {
            $attributes = [['name' => 'Notes', 'value' => "{$timestamp}\n" . ucfirst($status) . " by {$currentUser->full_name}\n\n$statusNote",]];
        }

        foreach ($attr as $name => $value) {
            $attributes[] = ['name' => $name, 'value' => $value,];
        }

        $response = ApigeeService::pushAppNote($app, $attributes, $status);

        if (200 === $response->status()) {
            $attributes = ApigeeService::getAppAttributes($response['attributes']);
            $app->update(['attributes' => $attributes, 'status' => $status]);

            return redirect()->back()->with('alert', "success:The status has been updated.");
        }

        return redirect()->back()->with('alert', "error:Sorry something went wrong updating the status.");
    }

    /**
     * @param User $user
     * @param ProductLocationService $productLocationService
     * @return Factory
     */
    public function createUserApp(ProductLocationService $productLocationService, User $user = null)
    {
        $appCreator = \Auth()->user();

        $users = User::whereNotNull(['email_verified_at'])->select(['profile_picture', 'email'])->get();

        $emails = $profiles = [];
        foreach ($users as $u) {
            $profiles[$u->email] = $u->profile_picture;
            if ($u->email === $appCreator->email) continue;
            array_push($emails, $u->email);
        }

        [$products, $countries] = $productLocationService->fetch();

        return view('templates.admin.apps.create', [
            'productCategories' => array_keys($products->toArray()),
            'appCreatorEmail' => $appCreator->email,
            'countries' => $countries ?? '',
            'userProfiles' => $profiles,
            'userEmails' => $emails,
            'products' => $products,
            'chosenUser' => $user,
        ]);
    }
}
