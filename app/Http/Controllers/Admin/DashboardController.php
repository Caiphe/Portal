<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusRequest;
use App\Services\ApigeeService;
use App\App;
use App\Country;
use App\Mail\ProductAction;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user->load('responsibleCountries');
        $isAdmin = $user->hasRole('admin');
        $responsibleCountriesIds = $user->responsibleCountries->pluck('id')->toArray();
        $responsibleCountriesCodes = $user->responsibleCountries->pluck('code')->implode('|');

        $appsByProductLocation = App::with(['developer', 'country', 'products'])
            ->whereNull('country_id')
            ->whereHas('products', function ($query) use ($isAdmin, $responsibleCountriesCodes) {
                $query
                    ->where('status', 'pending')
                    ->when(!$isAdmin, function ($query) use ($responsibleCountriesCodes) {
                        $query->whereRaw("`locations` REGEXP \"(" . $responsibleCountriesCodes . ")\"");
                    });
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
            ->byStatus('approved')
            ->union($appsByProductLocation)
            ->orderBy('updated_at', 'desc')
            ->paginate();

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
            return response()->json(['success' => false, 'body' => json_decode($response->body())], $responseStatus);
        }

        Mail::to(env('MAIL_TO_ADDRESS'))->send(new ProductAction($app, $validated, $currentUser));

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }
}
