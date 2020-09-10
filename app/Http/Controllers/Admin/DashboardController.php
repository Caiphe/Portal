<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusRequest;
use App\Services\ApigeeService;
use App\App;
use App\Country;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user->load('responsibleCountries');
        $isAdmin = $user->hasRole('admin');

        $apps = App::with(['developer', 'country', 'products'])
            ->whereHas('products', function ($query) use ($user, $isAdmin) {
                $query->where('status', 'pending');
                if (!$isAdmin) {
                    $responsibleCountriesCode = $user->responsibleCountries->pluck('code')->implode('|');
                    $query->whereRaw("CONCAT(\",\", `locations`, \",\") REGEXP \",(" . $responsibleCountriesCode . "),\"");
                }
            })
            ->byStatus('approved')
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
            $app->products()->updateExistingPivot($validated['product'], ['status' => $status, 'actioned_by' => $request->user()->id]);
        } else if ($request->ajax()) {
            return response()->json(['success' => false, 'body' => json_decode($response->body())], $responseStatus);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }
}
