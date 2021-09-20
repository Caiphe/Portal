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
        $users = User::with('roles');

        $users->when($request->has('q'), function($q) use($request) {
            $query = "%" . $request->q . "%";

            $q->where(function ($q) use($query) {
                $q->where('first_name', 'like', $query)
                    ->orWhere('last_name', 'like', $query);
            });
        })
            ->when($request->has('verified'), function($q) use($request) {
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
                    'fields' => ['first_name', 'last_name', 'email', 'roles_list'],
                    'defaultSortQuery' => $defaultSortQuery,
                    'modelName' => 'user',
                    'order' => $order,
                ], 200)
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
                'roles' => Role::all(),
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
            'roles' => 'required',
            'country' => 'nullable',
            'responsible_countries' => 'nullable',
            'responsible_groups' => 'nullable',
        ]);
        $randNum = rand(1, 24);
        $imageName = base64_encode(date('iYHs') . $randNum) . '.svg';
        $imagePath = 'public/profile/' . $imageName;
        \Storage::copy('public/profile/profile-' . $randNum . '.svg', $imagePath);

        $data['profile_picture'] = '/storage/profile/' . $imageName;

        $user = User::create($data);
        $user->roles()->sync([$data['roles']]);

        if (!is_null($request->country)) {
            $user->countries()->sync([$data['country']]);
        }

        if (!is_null($request->responsible_countries)) {
            $user->responsibleCountries()->sync($data['responsible_countries']);
        }

        if (!is_null($request->responsible_groups)) {
            $user->responsibleCountries()->sync($data['responsible_groups']);
        }

        return redirect()->route('admin.user.index')->with('alert', 'success:The user has been created');
    }

    public function edit(Request $request, User $user)
    {
        $statusSelectFilter = $request->get('status_select_filter', 'all');
        $countrySelectFilterCode = $request->get('country_select_filter', 'all');

        $user->load('roles', 'countries', 'responsibleCountries', 'responsibleGroups');
        $groups = Product::select('group')->where('group', '!=', 'Partner')->where('group', '!=', 'MTN')->groupBy('group')->get()->pluck('group', 'group');
        $groups = array_merge(['MTN' => 'General'], $groups->toArray());

        return view('templates.admin.users.edit', [
            'countrySelectFilterCode' => $countrySelectFilterCode,
            'countries' => Country::orderBy('name')->get(),
            'statusSelectFilter' => $statusSelectFilter,
            'roles' => Role::all(),
            'groups' => $groups,
            'user' => $user,
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
            'roles' => 'required',
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
        $user->roles()->sync($data['roles']);

        if (!is_null($request->country)) {
            $user->countries()->sync([$data['country']]);
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
