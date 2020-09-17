<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles');

        if ($request->has('q')) {
            $query = "%" . $request->q . "%";
            $users->where('first_name', 'like', $query)
                ->orWhere('last_name', 'like', $query);
        }

        if ($request->ajax()) {
            return response()
                ->view('components.admin.table-data', [
                    'collection' => $users->orderBy('first_name')->paginate(),
                    'fields' => ['first_name', 'last_name', 'email', 'roles_list'],
                    'modelName' => 'user'
                ], 200)
                ->header('Content-Type', 'text/html');
        }

        return view('templates.admin.users.index', [
            'users' => $users->paginate()
        ]);
    }

    public function edit(User $user)
    {
        $user->load('roles', 'countries', 'responsibleCountries');

        return view('templates.admin.users.edit', [
            'user' => $user,
            'roles' => Role::all(),
            'countries' => Country::all(),
        ]);
    }

    public function update(User $user, Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => [
                'email:rfc,dns',
                Rule::unique('users')->ignore($user->id),
            ],
            'roles' => 'required',
            'country' => 'nullable',
            'responsible_countries' => 'nullable',
        ]);

        $user->update($data);
        $user->roles()->sync([$data['roles']]);

        if (!is_null($request->country)) {
            $user->countries()->sync([$data['country']]);
        }

        if (!is_null($request->responsible_countries)) {
            $user->responsibleCountries()->sync($data['responsible_countries']);
        }

        return redirect()->route('admin.user.index')->with('success', 'The user has been updated');
    }

    public function create()
    {
        return view(
            'templates.admin.users.create',
            [
                'roles' => Role::all(),
                'countries' => Country::all(),
            ]
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email:rfc,dns|unique:users,email',
            'roles' => 'required',
            'country' => 'nullable',
            'responsible_countries' => 'nullable',
        ]);

        $user = User::create($data);
        $user->roles()->sync([$data['roles']]);

        if (!is_null($request->country)) {
            $user->countries()->sync([$data['country']]);
        }

        if (!is_null($request->responsible_countries)) {
            $user->responsibleCountries()->sync($data['responsible_countries']);
        }

        return redirect()->route('admin.user.index')->with('success', 'The user has been created');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.user.index')->with('alert', 'success:The user has been deleted.');
    }
}
