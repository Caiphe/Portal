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

    <div class="each-container">
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
        <div id="password-still-needs" class="invalid-feedback" role="alert"></div>
    </div>

</div>

<div class="flex-container bottom-section-container">
    <div class="each-container">
        <h2>Developer's apps</h2>
    </div>
    <div class="each-container">
        <a class="button create-btn" href="{{ route('admin.app.create', $user->id) }}">Create an app for this user</a>
    </div>
</div>

{{-- apps list --}}
<table id="dev-apps">
    <tr>
        <th>Name</th>
        <th>Keys</th>
        <th>Products</th>
        <th>Created</th>
        <th>Country</th>
        <th>Status</th>
    </tr>

    @if(!$user->getApps()->isEmpty())
        @foreach($user->getApps() as $app)
            <tr>
                <td><a href="{{ route('admin.dashboard.index', ['q' => $app->display_name]) }}" class="app-link">{{ $app->display_name }}</a></td>
                <td>{{ count($app->products) }}</td>
                <td>{{ count($app->products) }}</td>
                <td>{{ $app->created_at }}</td>
                <td><div class="country-flag" style="background-image: url('/images/locations/{{ $app->country->code }}.svg')"></div></td>
                <td><div class="status {{ ('approved' === $app->status)  ? 'active' : 'non-active' }}"></div></td>
            </tr>
        @endforeach
    @endif
</table>

@push('scripts')
    <script src="{{ mix('/js/templates/admin/users/scripts.js') }}"></script>
    <script>

        var selectedStatusFilter = document.getElementById("status-select-filter");
        var selectedCountryFilter = document.getElementById("country-select-filter");

        selectedStatusFilter.addEventListener('change', selectFilterChanged);
        selectedCountryFilter.addEventListener('change', selectFilterChanged);

        function selectFilterChanged(event) {

            var data = {
                status_select_filter: selectedStatusFilter,
                country_select_filter: selectedCountryFilter
            };

            var filteringHandled = handleChangedFilter("{{ $user->id }}", data);

            if (!filteringHandled) {
                event.preventDefault();

                addAlert('error', 'Something went wrong meanwhile filtering apps.')
            }

            function handleChangedFilter(userId, data) {

                var success = null;

                var xhr = new XMLHttpRequest();

                xhr.open('POST', '/admin/users/edit/' + userId);
                xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
                xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                xhr.send(JSON.stringify(data));

                xhr.onload = function() {

                    if (xhr.status === 200) {
                        success = true;
                    } else {
                        success = false;
                    }
                };

                return success;
            }
        }

        function handleTableSorting()
        {

        }
    </script>
@endpush
