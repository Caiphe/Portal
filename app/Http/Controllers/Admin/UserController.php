<?php

namespace App\Http\Controllers\Admin;

use App\App;
use App\Role;
use App\User;
use App\Country;
use App\Product;
use App\RoleUser;
use App\Notification;
use App\TwofaResetRequest;
use App\UserDeletionRequest;
use Illuminate\Http\Request;
use App\Services\ApigeeService;
use Mpociot\Teamwork\TeamInvite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserDeletionRequestMail;
use App\Mail\TwoFaResetConfirmationMail;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = $request->user();
        $order = $request->get('order', 'desc');
        $numberPerPage = (int)$request->get('number_per_page', '15');
        $sort = '';

        $users = User::with('roles', 'countries')
            ->withCount('apps')
            ->when(!$currentUser->hasRole('admin') && $currentUser->hasRole('opco'), function ($query) use ($currentUser) {
                $query->whereHas('countries', fn ($q) => $q->whereIn('code', $currentUser->responsibleCountries()->pluck('code')->toArray()));
            })
            ->when($request->has('q'), function ($q) use ($request) {
                $query = "%" . $request->q . "%";

                $q->where(function ($q) use ($query) {
                    $q->where('first_name', 'like', $query)
                        ->orWhere('last_name', 'like', $query)
                        ->orWhere('email', 'like', $query);
                });
            })
            ->when(!empty($request->get('status', '')), function ($q) use ($request) {
                $verified = $request->get('status');
                if ($verified === 'verified') {
                    $q->whereNotNull('email_verified_at');
                } else {
                    $q->whereNull('email_verified_at');
                }
            });

        if ($request->has('sort')) {
            $sort = $request->get('sort');

            if ($sort === 'roles') {
                $users->orderBy(
                    RoleUser::select('role_id')
                        ->whereColumn('role_user.user_id', 'users.id')
                        ->latest()
                        ->take(1),
                    $order
                );
            } else if($sort === 'status') {
                $users->orderBy('email_verified_at', $order);
            } else {
                $users->orderBy($sort, $order);
            }

            $order = ['asc' => 'desc', 'desc' => 'asc'][$order] ?? 'desc';
        }

        if ($request->ajax()) {
            return response()
                ->view('components.admin.list', [
                    'collection' => $users->paginate($numberPerPage),
                    'order' => $order,
                    'fields' => ['First name' => 'first_name', 'Last name' => 'last_name|addClass:not-on-mobile', 'Email' => 'email', 'Member since' => 'created_at|date:d M Y|addClass:not-on-mobile', 'Role' => 'roles|implode:, >label|addClass:not-on-mobile', 'Status' => 'status|splitToTag:,|addClass:not-on-mobile', 'apps' => 'apps_count|addClass:not-on-mobile'],
                    'modelName' => 'user',
                ], 200)
                ->header('Vary', 'X-Requested-With')
                ->header('Content-Type', 'text/html');
        }
        
        return view('templates.admin.users.index', [
            'users' => $users->paginate($numberPerPage),
            'order' => $order,
        ]);
    }

    public function create()
    {
        $groups = Product::select('group')->where('group', '!=', 'Partner')->where('group', '!=', 'MTN')->groupBy('group')->get()->pluck('group');
        $groups = array_merge(['MTN' => 'General'], $groups->toArray());
        $privateProducts = Product::where('access', 'private')->pluck('display_name', 'pid');

        $productLocations = Product::isPublic()
            ->WhereNotNull('locations')
            ->Where('locations', '!=', 'all')
            ->select('locations')
            ->get()
            ->implode('locations', ',');

        $locations = array_unique(explode(',', $productLocations));
        $countries = Country::whereIn('code', $locations)->orderBy('name')->get();

        return view(
            'templates.admin.users.create',
            [
                'roles' => Role::where('name', 'not like', 'team%')->get(),
                'countries' => $countries,
                'groups' => $groups,
                'isAdminUser' => auth()->user()->hasRole('admin'),
                'privateProducts' => $privateProducts,
            ]
        );
    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();

        $data['profile_picture'] = '/storage/profile/profile-' . rand(1, 32) . '.svg';

        $user = User::create($data);

        if ($request->has('roles')) {
            $user->roles()->sync($data['roles']);
        }

        if ($request->has('country')) {
            $user->countries()->sync($data['country']);
        }

        if ($request->has('responsible_countries')) {
            $user->responsibleCountries()->sync($data['responsible_countries']);
        }

        if ($request->has('responsible_groups')) {
            $user->responsibleGroups()->sync($data['responsible_groups']);
        }

        $user->assignedProducts()->sync($data['private_products'] ?? []);

        $user->sendEmailVerificationNotification();

        return redirect()->route('admin.user.index')->with('alert', 'success:The user has been created');
    }

    public function edit(Request $request, User $user)
    {
        $countrySelectFilterCode = $request->get('country-filter', 'all');
        $currentUser = $request->user();
        $user->load('roles', 'countries', 'responsibleCountries', 'responsibleGroups', 'assignedProducts');
        $groups = Product::select('group')->where('group', '!=', 'Partner')->where('group', '!=', 'MTN')->groupBy('group')->get()->pluck('group', 'group');
        $groups = array_merge(['MTN' => 'General'], $groups->toArray());
        $privateProducts = Product::where('access', 'private')->pluck('display_name', 'pid');
        $order = 'desc';

        $userTwoFaRequest = TwofaResetRequest::where(['user_id' => $user->id, 'approved_by' => null])->first();

        if ($request->has('sort')) {
            $order = ['asc' => 'desc', 'desc' => 'asc'][$request->get('order', 'desc')] ?? 'desc';
        }

        $productLocations = Product::isPublic()
            ->WhereNotNull('locations')
            ->Where('locations', '!=', 'all')
            ->select('locations')
            ->get()
            ->implode('locations', ',');

        $locations = array_unique(explode(',', $productLocations));
        $countries = Country::whereIn('code', $locations)->orderBy('name')->get();


        return view('templates.admin.users.edit', [
            'selectedCountryFilter' => $countrySelectFilterCode,
            'countries' => $countries,
            'roles' => Role::where('name', 'not like', 'team%')->get(),
            'groups' => $groups,
            'user' => $user,
            'order' => $order,
            'userRoleIds' => isset($user) ? $user->roles->pluck('id')->toArray() : [],
            'userCountryCodes' => $user->countries->pluck('code')->toArray() ?? [],
            'userResponsibleCountries' => $user->responsibleCountries()->pluck('code')->toArray(),
            'userResponsibleGroups' => $user->responsibleGroups()->pluck('group')->toArray(),
            'currentUserResponsibleCountries' => $currentUser->responsibleCountries()->pluck('code')->toArray(),
            'currentUserResponsibleGroups' => $currentUser->responsibleGroups()->pluck('group')->toArray(),
            'currentUser' => $currentUser,
            'isAdminUser' => $currentUser->hasRole('admin'),
            'duplicateCountries' => [],
            'userApps' => $user->getApps($countrySelectFilterCode, $order, $request->get('sort', 'name')),
            'privateProducts' => $privateProducts,
            'userAssignedProducts' => $user->assignedProducts->pluck('pid')->toArray(),
			'user_twofa_reset_request' => $userTwoFaRequest,
			'adminRoles' => array_unique(explode(',', $currentUser->getRolesListAttribute())),
            'deletionRequest' => UserDeletionRequest::where('user_email', $user->email)->first()
        ]);
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        $data = $request->validated();
        $user->update($data);
        
        if($request->user()->hasRole('admin'))
        {
            $user->roles()->sync($data['roles'] ?? []);
            $user->responsibleCountries()->sync($data['responsible_countries'] ?? []);
            $user->responsibleGroups()->sync($data['responsible_groups'] ?? []);
        }

        $user->countries()->sync($data['country'] ?? []);
        $user->assignedProducts()->sync($data['private_products'] ?? []);

        Notification::create([
            'user_id' => $user->id,
            'notification' => "Your profile has been updated. Please navigate to your <a href='/profile'>Profile</a> for more info.",
        ]);

        return redirect()->route('admin.user.index')->with('alert', 'success:The user has been updated');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.user.index')->with('alert', 'success:The user has been deleted.');
    }

    public function resetTwofaConfirm(User $user)
	{
        $admin = auth()->user()->full_name;
        $userRequest = TwofaResetRequest::where(['user_id' => $user->id,'approved_by' => null])->first();
        $userRequest->update(['approved_by' => $admin]);
		$user->update(['2fa'=> null]);

        Notification::create([
            'user_id' => $user->id,
            'notification' => "Your 2fa reset request has been approved. Please navigate to your <a href='/profile#twofa'>Profile</a> and set up your 2fa. ",
        ]);
        
		Mail::to($user->email)->send( new TwoFaResetConfirmationMail());

        return response()->json(['success' => true, 'code' => 200], 200);
	}

    public function requestUserDeletion(User $user)
    {
        $requestedBy = auth()->user()->full_name;
		$adminUsers = User::whereHas('roles', fn ($q) => $q->where('name', 'Admin'))->pluck('email')->toArray();
        $usersCountries = $user->countries->pluck('name')->toArray();
        $countries = $user->countries->pluck('name')->implode(', ');

        $checkExists = UserDeletionRequest::where('user_email', $user->email)->first();
        if($checkExists){
            return response()->json(['success' => false, 'code' => 400], 400);
        }

        UserDeletionRequest::create([
            'countries' => $countries,
            'requested_by' => $requestedBy,
            'user_email' => $user->email,
            'user_name' => $user->full_name,
        ]);

        Mail::bcc($adminUsers)->send(new UserDeletionRequestMail($user, $usersCountries));
        return response()->json(['success' => true, 'code' => 200], 200);
    }

    public function delectionAction(User $user)
    {    
        $deleted = ApigeeService::deleteDeveloper($user);

        if($deleted->status() === 400){
            return response()->json(['success' => false, 'code' => 400], 400);
        }

        // Detach user's roles
        if($user->roles){
            $user->roles()->detach();
        }

        // Detach user's countries
        if($user->countries){
            $user->countries()->detach();
        }

        if($user->responsibleCountries){
            $user->responsibleCountries()->detach();
        }

        // Detach the user from the private products
        if($user->assignedProducts){
            $user->assignedProducts()->detach();
        }

        // Delete user's apps
        if($user->apps){
            $user->apps()->delete();
        }

        // Delete users authentication logs
        if($user->authentications){
            $user->authentications()->delete();
        }

        if($user->twoFaResetRequest){
            $user->twoFaResetRequest()->delete();
        }

        if($user->OpcoRoleRequest){
            $user->OpcoRoleRequest()->delete();
        }

        // Delete users teams / and delete apps associated with the team
        if($user->team){
            $user->team()->detach();

            foreach($user->team as $team){
                // Delete team apps
                $appNamesToDelete = App::where('team_id', $team->id)->pluck('name')->toArray();
                if($appNamesToDelete) {
                    App::whereIn('name', $appNamesToDelete)->delete();
                }

                // Delete team invites if any
                $teamsInvites = TeamInvite::where('team_id', $team->id)->get();
                if($teamsInvites){
                    $teamsInvites->each->delete();
                }

                // removes members from the team
                $teamMembers = $team->users->pluck('id')->toArray();
                if($teamMembers){
                    $team->users()->detach($teamMembers);
                }

                $team->delete();
            }
        }

        // delete users notification
        if($user->notifications){
            $user->notifications()->forceDelete();
        }

        $deletionRequest = UserDeletionRequest::where('user_email', $user->email)->first();
        if(!isset($deletionRequest->approved_by)){
            $deletionRequest->update([
                'approved_by' => auth()->user()->full_name, 
                'approved_at' => now()
            ]);
        }

        $user->delete();

        return response()->json(['success' => true, 'code' => 200], 200);
    }
}
