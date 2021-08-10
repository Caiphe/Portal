<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusRequest;
use App\Services\ApigeeService;
use App\App;
use App\Country;
use App\Http\Controllers\AppController;
use App\Mail\KycStatusUpdate;
use App\Mail\ProductAction;
use Illuminate\Support\Facades\Mail;
use \Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        $user->load(['responsibleCountries', 'responsibleGroups']);
        $isAdmin = $user->hasRole('admin');
        $responsibleCountriesCodes = $user->responsibleCountries->pluck('code')->toArray();
        $responsibleGroups = $user->responsibleGroups->pluck('group')->toArray();
        $searchTerm = "%" . $request->get('q', '') . "%";
        $hasSearchTerm = $searchTerm !== '%%';
        $searchCountries = $request->get('countries');
        $hasCountries = $request->has('countries') && !is_null($searchCountries);
        $notAdminNoResponsibleCountries = !$isAdmin && empty($responsibleCountriesCodes);
        $notAdminNoResponsibleGroups = !$isAdmin && empty($responsibleGroups);
        $appStatus = $request->get('app-status', 'pending');
        $productStatus = $request->get('product-status', 'approved');


        if (($notAdminNoResponsibleCountries || $notAdminNoResponsibleGroups) && $request->ajax()) {
            return response()
                ->view('templates.admin.dashboard.data', [
                    'apps' => App::where('country_code', 'none')->paginate(),
                    'countries' => Country::all(),
                ], 200)
                ->header('Content-Type', 'text/html');
        } else if (($notAdminNoResponsibleCountries || $notAdminNoResponsibleGroups)) {
            return view('templates.admin.dashboard.index', [
                'apps' => App::where('country_code', 'none')->paginate(),
                'countries' => Country::all(),
            ]);
        }

        $apps = App::with(['developer', 'country', 'products'])
            ->whereNotNull('country_code')
            ->when(!$isAdmin, function ($query) use ($responsibleCountriesCodes, $responsibleGroups) {
                $query->whereIn('country_code', $responsibleCountriesCodes)
                    ->whereHas('products', function ($q) use ($responsibleGroups) {
                        $q->whereIn('group', $responsibleGroups);
                    });
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
            ->whereHas('products', function ($q) use ($appStatus, $productStatus) {
                $allowedFilterStatuses = ['approved', 'revoked'];
                $shallowFilterStatuses = ['atleast_approved', 'atleast_revoked'];

                if ($appStatus === 'pending' || !in_array($productStatus, $shallowFilterStatuses)) {
                    $q->where('status', 'pending');
                } elseif (in_array($productStatus, $shallowFilterStatuses)) {
                    $filterStatus = substr($productStatus,8);
                    if (in_array($filterStatus, $allowedFilterStatuses)) {
                        $q->where('status', $filterStatus);
                    }
                } elseif(in_array($productStatus, $allowedFilterStatuses) || in_array($appStatus, $allowedFilterStatuses)){
                    $q->whereIn('status', [ $appStatus, $productStatus ]);
                }else {
                    $q->where('status', in_array($productStatus, $allowedFilterStatuses) ? [ $productStatus ] : [ $allowedFilterStatuses ]);
                }
            })
            ->when($hasCountries, function ($q) use ($searchCountries) {
                $q->where('country_code', $searchCountries);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate();

        if ($request->ajax()) {
            return response()
                ->view('templates.admin.dashboard.data', [
                    'apps' => $apps,
                    'countries' => Country::orderBy('name')->pluck('name', 'code'),
                ], 200)
                ->header('Content-Type', 'text/html');
        }

        return view('templates.admin.dashboard.index', [
            'apps' => $apps,
            'countries' => Country::orderBy('name')->pluck('name', 'code'),
            'selectedCountry' => $request->get('countries', ''),
            'selectedStatus' => $request->get('status', 'pending')
        ]);
    }

    public function update(UpdateStatusRequest $request)
    {
        $validated = $request->validated();
        $app = App::with('developer')->find($validated['app']);
        $currentUser = $request->user();
        $status = [
            'approve' => 'approved',
            'revoke' => 'revoked',
            'pending' => 'pending'
        ][$validated['action']] ?? 'pending';

        $statusNote = $request->get('statusNote', '');

        $credentials = ApigeeService::getAppCredentials($app);
        $credentials = $validated['for'] === 'staging' ? $credentials[0] : end($credentials);

        $developerId = $app->developer->email ?? $app->developer_id;

        $response = ApigeeService::updateProductStatus($developerId, $app->name, $credentials['consumerKey'], $validated['product'], $validated['action']);
        $responseStatus = $response->status();
        if (preg_match('/^2/', $responseStatus)) {

            $app->products()->updateExistingPivot($validated['product'], ['status' => $status, 'actioned_by' => $currentUser->id, 'actioned_at' => date('Y-m-d H:i:s'), 'status_note' => $statusNote]);
        } else if ($request->ajax()) {
            $body = json_decode($response->body());
            return response()->json(['success' => false, 'body' => $body->message], $responseStatus);
        }

        Mail::to(env('MAIL_TO_ADDRESS'))->send(new ProductAction($app, $validated, $currentUser));

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
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

        Mail::to($app->developer->email)->send(new KycStatusUpdate($app->developer, $app));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'body' => "The KYC status was updated to {$data['kyc_status']}"]);
        }

        return redirect()->back()->with('alert', "success:The KYC status was updated to {$data['kyc_status']}");
    }

    public function updateAppStatus(App $app, Request $request)
    {
        $status = $request->get('status');
        $statusNote = $request->get('status-note');

        $attr = $app->attributes;
        $attributes = [];
        if (isset($attr['Notes'])) {
            $attr['Notes'] = $statusNote ?? $attr['Notes'];
        } else if (!empty($statusNote)) {
            $attributes = [['name' => 'Notes', 'value' => $statusNote,]];
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
}
