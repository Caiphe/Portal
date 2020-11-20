<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusRequest;
use App\Services\ApigeeService;
use App\App;
use App\Country;
use App\Mail\ProductAction;
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
        $hasCountries = $request->has('countries') && !is_null($searchCountries[0]);
        $notAdminNoResponsibleCountries = !$isAdmin && empty($responsibleCountriesCodes);
        $notAdminNoResponsibleGroups = !$isAdmin && empty($responsibleGroups);

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
            ->when(!$hasSearchTerm && !$hasCountries, function ($q) {
                $q->whereNotNull('live_at')
                    ->whereHas('products', function ($query) {
                        $query->whereNull('actioned_at');
                    });
            })
            ->when($hasSearchTerm, function ($q) use ($searchTerm) {
                $q->where(function ($query) use ($searchTerm) {
                    $query->where('display_name', 'like', $searchTerm)
                        ->orWhere('aid', 'like', $searchTerm);
                });
            })
            ->when($hasCountries, function ($q) use ($searchCountries) {
                $q->where(function ($query) use ($searchCountries) {
                    $query->whereHas('country', function ($q) use ($searchCountries) {
                        $q->whereIn('code', $searchCountries);
                    });
                });
            })
            ->byStatus('approved')
            ->orderBy('updated_at', 'desc')
            ->paginate();

        if ($request->ajax()) {
            return response()
                ->view('templates.admin.dashboard.data', [
                    'apps' => $apps,
                    'countries' => Country::all(),
                ], 200)
                ->header('Content-Type', 'text/html');
        }

        return view('templates.admin.dashboard.index', [
            'apps' => $apps,
            'countries' => Country::all(),
        ]);
    }

    public function update(UpdateStatusRequest $request)
    {
        $validated = $request->validated();
        $app = App::with('developer')->where('name', $validated['app'])->first();
        $currentUser = $request->user();
        $status = [
            'approve' => 'approved',
            'revoke' => 'revoked',
            'pending' => 'pending'
        ][$validated['action']] ?? 'pending';

        $credentials = ApigeeService::getAppCredentials($app);
        $credentials = $validated['for'] === 'staging' ? $credentials[0] : end($credentials);

        $developerId = $app->developer->email ?? $app->developer_id;

        $response = ApigeeService::updateProductStatus($developerId, $validated['app'], $credentials['consumerKey'], $validated['product'], $validated['action']);
        $responseStatus = $response->status();
        if (preg_match('/^2/', $responseStatus)) {
            $app->products()->updateExistingPivot($validated['product'], ['status' => $status, 'actioned_by' => $currentUser->id, 'actioned_at' => date('Y-m-d H:i:s')]);
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
}
