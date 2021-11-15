<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Http\Controllers\Controller;
use App\Product;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\App;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = auth()->user();

        $users = User::with('roles', 'countries')
            ->when($currentUser->hasRole('opco'), function($query) use($currentUser) {
                $query->whereHas('countries', fn ($q) => $q->whereIn('code', $currentUser->responsibleCountries()->pluck('code')->toArray()));
            });

        $users->when($request->has('q'), function($q) use($request) {
            $query = "%" . $request->q . "%";

            $q->where(function ($q) use($query) {
                $q->where('first_name', 'like', $query)
                    ->orWhere('last_name', 'like', $query)
                    ->orWhere('email', 'like', $query);
            });
        })
            ->when(!empty($request->get('verified', '')), function($q) use($request) {
                $verified = $request->get('verified');
                if ($verified === 'verified') {
                    $q->whereNotNull('email_verified_at');
                } else {
                    $q->whereNull('email_verified_at');
                }
            });

        $order = 'desc';

        $defaultSortQuery = array_diff_key($request->query(), ['sort' => true, 'order' => true]);

        if (!empty($defaultSortQuery)) {
            $defaultSortQuery = '&' . http_build_query($defaultSortQuery);
        } else {
            $defaultSortQuery = '';
        }

        if ($request->has('sort')) {
            $order = $request->get('order', 'desc');
            $users->orderBy($request->get('sort', $order));
            $order = ['asc' => 'desc', 'desc' => 'asc'][$order] ?? 'desc';
        }

        if ($request->ajax()) {
            return response()
                ->view('components.admin.users-data', [
                    'collection' => $users->orderBy('first_name')->paginate($request->get('per-page', 10)),
                    'fields' => ['first_name', 'last_name', 'email', 'member_since', 'roles_list', 'status', 'apps'],
                    'defaultSortQuery' => $defaultSortQuery,
                    'modelName' => 'user',
                    'order' => $order,
                ], 200)
                ->header('Vary', 'X-Requested-With')
                ->header('Content-Type', 'text/html');
        }

        return view('templates.admin.users.index', [
            'users' => $users->orderBy('first_name')->paginate($request->get('per-page', 10)),
            'defaultSortQuery' => $defaultSortQuery,
            'order' => $order,
        ]);
    }

    public function create()
    {
        $groups = Product::select('group')->where('group', '!=', 'Partner')->where('group', '!=', 'MTN')->groupBy('group')->get()->pluck('group');
        $groups = array_merge(['MTN' => 'General'], $groups->toArray());

        return view(
            'templates.admin.users.create',
            [
                'roles' => Role::where('name', 'not like', 'team%')->get(),
                'countries' => Country::orderBy('name')->get(),
                'groups' => $groups
            ]
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email:rfc,dns|unique:users,email',
            'password' => 'required|confirmed',
            'roles' => 'nullable',
            'country' => 'nullable',
            'responsible_countries' => 'nullable',
            'responsible_groups' => 'nullable',
        ]);

        $data['profile_picture'] = '/storage/profile/profile-' . rand(1, 32) . '.svg';


        $user = User::create($data);

        if ($request->has('roles')) {
            $user->roles()->sync($data['roles']);
        }

        if ($request->has('country')) {
            $user->countries()->sync([$data['country']]);
        }

        if ($request->has('responsible_countries')) {
            $user->responsibleCountries()->sync($data['responsible_countries']);
        }

        if ($request->has('responsible_groups')) {
            $user->responsibleGroups()->sync($data['responsible_groups']);
        }

        $user->sendEmailVerificationNotification();

        return redirect()->route('admin.user.index')->with('alert', 'success:The user has been created');
    }

    public function edit(Request $request, User $user)
    {
        $countrySelectFilterCode = $request->get('country-filter', 'all');
        $order = 'desc';
        $defaultSortQuery = array_diff_key($request->query(), ['sort' => true, 'order' => true]);

        if (!empty($defaultSortQuery)) {
            $defaultSortQuery = '&' . http_build_query($defaultSortQuery);
        } else {
            $defaultSortQuery = '';
        }

        if ($request->has('sort')) {
            $order = ['asc' => 'desc', 'desc' => 'asc'][$request->get('order', 'desc')] ?? 'desc';
        }

        $currentUser = $request->user();
        $user->load('roles', 'countries', 'responsibleCountries', 'responsibleGroups');
        $groups = Product::select('group')->where('group', '!=', 'Partner')->where('group', '!=', 'MTN')->groupBy('group')->get()->pluck('group', 'group');
        $groups = array_merge(['MTN' => 'General'], $groups->toArray());

        return view('templates.admin.users.edit', [
            'selectedCountryFilter' => $countrySelectFilterCode,
            'countries' => Country::orderBy('name')->get(),
            'roles' => Role::where('name', 'not like', 'team%')->get(),
            'groups' => $groups,
            'user' => $user,
            'order' => $order,
            'defaultSortQuery' => $defaultSortQuery,
            'userRoleIds' => isset($user) ? $user->roles->pluck('id')->toArray() : [],
            'userCountryCodes' => $user->countries->pluck('code')->toArray() ?? [],
            'userResponsibleCountries' => isset($currentUser) ? $currentUser->responsibleCountries()->pluck('code')->toArray() : [],
            'userResponsibleGroups' => isset($currentUser) ? $currentUser->responsibleGroups()->pluck('group')->toArray() : [],
            'currentUser' => $currentUser,
            'isAdminUser' => $currentUser->hasRole('admin'),
            'duplicateCountries' => [],
            'userApps' => $user->getApps($countrySelectFilterCode, $order, $request->get('sort', 'name')),
        ]);
    }

    public function update(User $user, Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|max:140',
            'last_name' => 'required|max:140',
            'email' => [
                'email:rfc,dns',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'sometimes|confirmed',
            'roles' => 'nullable',
            'country' => 'nullable',
            'responsible_countries' => 'nullable',
            'responsible_groups' => 'nullable',
        ]);

        if (is_null($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);
        if ($request->has('roles')) {
            $user->roles()->sync($data['roles']);
        }

        if ($request->has('country')) {
            $user->countries()->sync($data['country']);
        }

        $user->responsibleCountries()->sync($data['responsible_countries'] ?? []);
        $user->responsibleGroups()->sync($data['responsible_groups'] ?? []);

        return redirect()->route('admin.user.index')->with('alert', 'success:The user has been updated');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.user.index')->with('alert', 'success:The user has been deleted.');
    }
}
