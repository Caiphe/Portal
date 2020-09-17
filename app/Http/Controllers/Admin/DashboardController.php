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
        $user = auth()->user();
        $user->load('responsibleCountries');
        $isAdmin = $user->hasRole('admin');
        $responsibleCountriesIds = $user->responsibleCountries->pluck('id')->toArray();
        $responsibleCountriesCodes = $user->responsibleCountries->pluck('code')->implode('|');
        $hasSearchTerm = $request->has('q');
        $searchTerm = "%" . $request->get('q', '') . "%";
        $hasCountries = $request->has('countries');
        $searchCountries = $request->get('countries');

        $appsByProductLocation = App::with(['developer', 'country', 'products'])
            ->whereNull('country_id')
            ->whereHas('products', function ($query) use ($isAdmin, $responsibleCountriesCodes, $hasCountries, $searchCountries) {
                $query
                    ->where('status', 'pending')
                    ->when(!$isAdmin, function ($query) use ($responsibleCountriesCodes) {
                        $query->whereRaw("`locations` REGEXP \"(" . $responsibleCountriesCodes . ")\"");
                    })
                    ->when($hasCountries, function ($q) use ($searchCountries) {
                        $q->whereRaw("`locations` REGEXP \"(" . implode(',', $searchCountries) . ")\"");
                    });
            })
            ->when($hasSearchTerm, function ($q) use ($searchTerm) {
                $q->where('display_name', 'like', $searchTerm);
            })
            ->byStatus('approved');

        $apps = App::with(['developer', 'country', 'products'])
            ->whereNotNull('country_id')
            ->when(!$isAdmin, function ($query) use ($responsibleCountriesIds) {
                $query->whereIn('country_id', $responsibleCountriesIds);
            })
            ->whereHas('products', function ($query) {
                $query->where('status', 'pending');
            })
            ->when($hasSearchTerm, function ($q) use ($searchTerm) {
                $q->where('display_name', 'like', $searchTerm);
            })
            ->when($hasCountries, function ($q) use ($searchCountries) {
                $q->whereHas('country', function ($q) use ($searchCountries) {
                    $q->whereIn('code', $searchCountries);
                });
            })
            ->byStatus('approved')
            ->union($appsByProductLocation)
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

        $credentials = ApigeeService::get('apps/' . $app->aid)['credentials'];
        $credentials = ApigeeService::getLatestCredentials($credentials);

        $developerId = $app->developer->email ?? $app->developer_id;

        $response = ApigeeService::updateProductStatus($developerId, $validated['app'], $credentials['consumerKey'], $validated['product'], $validated['action']);
        $responseStatus = $response->status();
        if (preg_match('/^2/', $responseStatus)) {
            $app->products()->updateExistingPivot($validated['product'], ['status' => $status, 'actioned_by' => $currentUser->id]);
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
