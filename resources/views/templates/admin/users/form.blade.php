@csrf

<div class="editor-field half">
    <h2>User details</h2>

    <label class="editor-field-label half">
        <h3>First name</h3>
        <input type="text" name="first_name" placeholder="First name" value="{{ $user->first_name ?? old('first_name') }}" autocomplete="off" maxlength="140">
    </label>

    <label class="editor-field-label half">
        <h3>Last name</h3>
        <input type="text" name="last_name" placeholder="Last name" value="{{ $user->last_name ?? old('last_name') }}" autocomplete="off" maxlength="140">
    </label>

    <label class="editor-field-label">
        <h3>Email address</h3>
        <input type="text" name="email" placeholder="Email" value="{{ $user->email ?? old('email') }}" autocomplete="off" maxlength="140">
    </label>

    @if(isset($user))
    <div class="editor-field-label half">
        <h3>Member since</h3>
        <div class="editor-field-copy">{{ $user->created_at->diffForHumans() }}</div>
    </div>
    @endif

    <label @class([
        'editor-field-label',
        'half' => isset($user)
    ])>
        <h3>Countries of operation</h3>
        <x-multiselect id="country" name="country" label="Select country" :options="$countries->pluck('name', 'code')->toArray()" :selected="$userCountryCodes ?? []"/>
    </label>

    <label class="editor-field-label half">
        <h3>Password</h3>
        <input id="password" type="password" name="password" placeholder="Password" autocomplete="new-password">
        <button type="button" class="reset show-password" onclick="togglePasswordVisibility(this)"></button>
    </label>

    <label class="editor-field-label half">
        <h3>Confirm Password</h3>
        <input id="password-confirm" type="password" name="password_confirmation" placeholder="Confirm password" autocomplete="new-password">
        <button type="button" class="reset show-password" onclick="togglePasswordVisibility(this)"></button>
    </label>

    <label class="editor-field-label password-strength">
        <div id="password-strength">Password strength</div>
        <div id="password-still-needs" role="alert"></div>
    </label>
</div>

@if($isAdminUser)
<div class="editor-field half">
    <h2>Groups and roles</h2>

    <label class="editor-field-label">
        <h3><b>Groups</b> this user is responsible for</h3>
        <x-multiselect id="responsible_groups" name="responsible_groups" label="Select groups" :options="$groups" :selected="$userResponsibleGroups ?? []"/>
    </label>

    <label class="editor-field-label">
        <h3><b>Role</b> this user is responsible for</h3>
        <x-multiselect id="roles" name="roles" label="Select role" :options="$roles->pluck('label', 'id')->toArray()" :selected="$userRoleIds ?? []"/>
    </label>

    <label class="editor-field-label">
        <h3><b>Countries</b> this user is responsible for</h3>
        <x-multiselect id="responsible_countries" name="responsible_countries" label="Select country" :options="$countries->pluck('name', 'code')->toArray()" :selected="$userResponsibleCountries ?? []"/>
    </label>
</div>
@endif

@push('scripts')
<script src="{{ mix('/js/templates/admin/users/scripts.js') }}"></script>
@endpush
