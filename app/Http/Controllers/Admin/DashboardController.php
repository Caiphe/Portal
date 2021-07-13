<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusRequest;
use App\Services\ApigeeService;
use App\App;
use App\Country;
use App\Mail\KycStatusUpdate;
use App\Mail\ProductAction;
use App\Services\AppAccess\AppAccess;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Request;

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
        $status = $request->get('status', 'pending');

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
                        ->orWhereHas('developer', function($q) use($searchTerm){
                            $q->where('first_name', 'like', $searchTerm)
                                ->orWhere('last_name', 'like', $searchTerm)
                                ->orWhere('email', 'like', $searchTerm);
                        });
                });
            })
            ->when($status !== 'all', function ($q) use($status) {
                if ($status === 'pending') {
                    $q->whereHas('products', function($query){
                        $query->where('status', 'pending');
                    });
                } else {
                    $q->where('status', $status);
                }
            })
            ->when($hasCountries, function ($q) use ($searchCountries) {
                $q->where('country_code', $searchCountries);
            })
            ->byStatus('approved')
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

    public function update(UpdateStatusRequest $request, AppAccess $appAccess)
    {
        $validated = $request->validated();
        $app = App::with('developer')->find($validated['app']);
        $currentUser = $request->user();
        $status = [
            'approve' => 'approved',
            'revoke' => 'revoked',
            'pending' => 'pending'
        ][$validated['action']] ?? 'pending';

        $credentials = ApigeeService::getAppCredentials($app);
        $credentials = $validated['for'] === 'staging' ? $credentials[0] : end($credentials);

        $developerId = $app->developer->email ?? $app->developer_id;

        $response = ApigeeService::updateProductStatus($developerId, $app->name, $credentials['consumerKey'], $validated['product'], $validated['action']);
        $responseStatus = $response->status();
        if (preg_match('/^2/', $responseStatus)) {
            $app->products()->updateExistingPivot($validated['product'], ['status' => $status, 'actioned_by' => $currentUser->id, 'actioned_at' => date('Y-m-d H:i:s')]);

            $data = [
                'aid' => $app->aid,
                'appName' => $app->name,
                'comment' => $request->get('additional_status_change_note') ?: "",
                'status' => $validated['action']
            ];

            $options = [
                'callbackUrl' => '',
                'apiProducts' => ''
            ];

            $appAccess->setApigeeService(new ApigeeService());

            if ('approve' === $validated['action']) {
                $appAccess->approveAccess($data, auth()->user(), 'app', $options);
            } elseif ('revoked' === $validated['action']) {
                $appAccess->revokeAccess($data, auth()->user(), 'app', $options);
            }

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
}
