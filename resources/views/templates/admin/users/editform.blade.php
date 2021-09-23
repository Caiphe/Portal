@php
    $userRoleIds = isset($user) ? $user->roles->pluck('id')->toArray() : [];
    $userCountryCode = $user->countries[0]->pivot->country_code ?? 0;
    $userResponsibleCountries = isset($user) ? $user->responsibleCountries()->pluck('code')->toArray() : [];
    $userResponsibleGroups = isset($user) ? $user->responsibleGroups()->pluck('group')->toArray() : [];
@endphp

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

        <div class="field-container">
            <label class="control-label">Country of residence</label>
            <select name="country" autocomplete="off">
                <option value="">Select country</option>
                @foreach($countries as $country)
                    <option @if($userCountryCode === $country->code) selected @endif value="{{ $country->code }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>

    </div>

    <div class="each-container roles-groups">
        <h2>Groups & Roles</h2>

        <div class="form-control mb">
            <label class="control-label">Groups this user is responsible for</label>
            <x-multiselect id="responsible_groups" name="responsible_groups" label="Select groups" :options="$groups" :selected="$userResponsibleGroups"/>
        </div>

        <div class="form-control mb">
            <label class="control-label">Role this user is responsible for</label>
            <x-multiselect id="roles" name="roles" label="Select role" :options="$roles->pluck('label', 'id')->toArray()" :selected="$userRoleIds"/>
        </div>

        <div class="form-control mb">
            <label class="control-label">Countries this user is responsible for</label>
            <x-multiselect id="responsible_countries" name="responsible_countries" label="Select country" :options="$countries->pluck('name', 'code')->toArray()" :selected="$userResponsibleCountries"/>
        </div>
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
                @foreach($countries as $country)
                    @if(in_array($country->code, $userResponsibleCountries))
                        <option value="{{ $country->code }}" {{ (($selectedCountryFilter === $country->code) ? 'selected': '') }}>{{ $country->name }}</option>
                    @endif
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

    @if(!$user->getApps($selectedCountryFilter, $order, $sort)->isEmpty())
        @foreach($user->getApps($selectedCountryFilter, $order, $sort) as $app)
            @if(in_array($app->country_code, $userResponsibleCountries))
                <tr class="user-app" data-country="{{ $app->country_code }}">
                    <td><a href="{{ route('admin.dashboard.index', ['q' => $app->display_name, 'product-status' => 'all']) }}" class="app-link">{{ $app->display_name }}</a></td>
                    <td>{{ count($app->products) }}</td>
                    <td>{{ $app->created_at->format('Y-m-d') }}</td>
                    <td><div class="country-flag" style="background-image: url('/images/locations/{{ $app->country->code }}.svg')"></div></td>
                    <td><div class="status {{ ('approved' === $app->status)  ? 'active' : 'non-active' }}"></div></td>
                </tr>
            @endif
        @endforeach
    @endif
</table>

@push('scripts')
    <script src="{{ mix('/js/templates/admin/users/scripts.js') }}"></script>
@endpush
