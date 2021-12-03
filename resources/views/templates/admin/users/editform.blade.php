@csrf

<div class="flex-container">
    <div class="each-container">
        <h2>Details</h2>

        <div class="field-container">
            <label class="control-label">First Name</label>
            <input type="text" class="long" name="first_name" placeholder="First name" value="{{ $user->first_name ?? old('first_name') }}" autocomplete="off" maxlength="140">
        </div>

        <div class="field-container">
            <label class="control-label">Last Name</label>
            <input type="text" class="long" name="last_name" placeholder="Last name" value="{{ $user->last_name ?? old('last_name') }}" autocomplete="off" maxlength="140">
        </div>

        <div class="field-container">
            <label class="control-label">Email</label>
            <input type="text" class="long" name="email" placeholder="Email" value="{{ $user->email ?? old('email') }}" autocomplete="off" maxlength="140">
        </div>

        <div class="field-container">
            <label class="control-label">Member since</label>

            <div class="block-membership">
                {{ $user->created_at->diffForHumans() }}
            </div>
        </div>

        <div class="field-container wrap">
            <label class="control-label">Countries of operation</label>
            <x-multiselect id="country" name="country" label="Select country" :options="$countries->pluck('name', 'code')->toArray()" :selected="$userCountryCodes"/>
        </div>

    </div>

    <div class="each-container roles-groups">
        @if($isAdminUser)
        <h2>Groups</h2>

        <div class="form-control mb">
            <label class="control-label">Groups this user is responsible for</label>
            <x-multiselect id="responsible_groups" name="responsible_groups" label="Select groups" :options="$groups" :selected="$userResponsibleGroups"/>
        </div>

        <h2 class="role-heading">Roles</h2>

        <div class="form-control">
            <label class="control-label">Role this user is responsible for</label>
            <x-multiselect id="roles" name="roles" label="Select role" :options="$roles->pluck('label', 'id')->toArray()" :selected="$userRoleIds"/>
        </div>

        <div class="form-control">
            <label class="control-label">Countries this user is responsible for</label>
            <x-multiselect id="responsible_countries" name="responsible_countries" label="Select country" :options="$countries->pluck('name', 'code')->toArray()" :selected="$userResponsibleCountries"/>
        </div>
        @endif

    </div>

</div>

<div class="flex-container">
    <div class="each-container">
        <h2>Password</h2>
        <div class="field-container">
            <label class="control-label">Password</label>
            <input id="password" type="password" class="long" name="password" placeholder="Password" autocomplete="new-password">
            <button type="button" class="fab show-password" onclick="togglePasswordVisibility(this)"></button>
        </div>
        <div class="field-container">
            <label class="control-label">Confirm Password</label>
            <input id="password-confirm" type="password" class="long" name="password_confirmation" placeholder="Confirm password" autocomplete="new-password">
            <button type="button" class="fab show-password" onclick="togglePasswordVisibility(this)"></button>
        </div>
        <div class="field-container">
            <label class="control-label"></label>
            <div id="password-strength">Password strength</div>
        </div>
    </div>
    <div class="each-container">
        {{-- Password Strength goes here --}}
        <div id="password-still-needs" class="invalid-feedback users-password-check" role="alert"></div>
    </div>

</div>

<div class="flex-container bottom-section-container">
    <div class="each-container">
        <h2>Developer's apps</h2>
        <div class="each-select-block">
            <label>Country</label>
            <select name="country-filter" id="country-filter">
                <option value="all">All</option>
                    @foreach($userApps->pluck('country')->unique('name') as $country)
                        @if(!$country || (!in_array($country->code, $currentUserResponsibleCountries) && !$isAdminUser)) @continue @endif
                        <option value="{{ $country->code }}" {{ (($selectedCountryFilter === $country->code) ? 'selected': '') }}>{{ $country->name }}</option>
                    @endforeach
            </select>
        </div>
    </div>
    <div class="each-container">
        <a class="button create-btn" href="{{ route('admin.app.create', $user->id) }}">Create an app for this user</a>
    </div>
</div>

{{-- apps list --}}
<table id="dev-apps">
    <thead>
        <tr>
            <th><a style="color: #ffffff" href="?sort=name&order={{ $order . $defaultSortQuery }}">Name @svg('chevron-sorter', '#fff')</a></th>
            <th>Products</th>
            <th><a style="color: #ffffff" href="?sort=created_at&order={{ $order . $defaultSortQuery }}">Registered @svg('chevron-sorter', '#fff')</a></th>
            <th>Country</th>
            <th>Status</th>
        </tr>
    </thead>

    @if(!$userApps->isEmpty())
        @foreach($userApps as $app)
        @if(!(in_array($app->country_code, $currentUserResponsibleCountries) || $isAdminUser)) @continue @endif
        <tr class="user-app" data-country="{{ $app->country_code }}">
            <td><a href="{{ route('admin.dashboard.index', ['aid' => $app->aid]) }}" class="app-link">{{ $app->display_name }}</a></td>
            <td>{{ count($app->products) }}</td>
            <td>{{ $app->created_at->format('Y-m-d') }}</td>
            <td><div class="country-flag" style="background-image: url('/images/locations/{{ $app->country_code ?? 'globe' }}.svg')"></div></td>
            <td><div class="status app-status-{{ $app->status }}"></div></td>
        </tr>
        @endforeach
    @else
    <tr>
        <td>Developer currently has no apps</td>
    </tr>
    @endif
</table>

@push('scripts')
    <script src="{{ mix('/js/templates/admin/users/scripts.js') }}"></script>
@endpush
