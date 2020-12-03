@php
    $userRoleIds = isset($user) ? $user->roles->pluck('id')->toArray() : [];
    $userCountryCode = $user->countries[0]->pivot->country_code ?? 0;
    $userResponsableCountries = isset($user) ? $user->responsibleCountries()->pluck('code')->toArray() : [];
    $userResponsableGroups = isset($user) ? $user->responsibleGroups()->pluck('group')->toArray() : [];
@endphp

@csrf

<div class="editor-field">
    <h2>First name</h2>
    <input type="text" class="long" name="first_name" placeholder="First name" value="{{ $user->first_name ?? old('first_name') }}" autocomplete="off">
</div>

<div class="editor-field">
    <h2>Last name</h2>
    <input type="text" class="long" name="last_name" placeholder="Last name" value="{{ $user->last_name ?? old('last_name') }}" autocomplete="off">
</div>

<div class="editor-field">
    <h2>Email</h2>
    <input type="text" class="long" name="email" placeholder="Email" value="{{ $user->email ?? old('email') }}" autocomplete="off">
</div>

<div class="editor-field">
    <h2>Password</h2>
    <input id="password" type="password" class="long" name="password" placeholder="Password" autocomplete="new-password">
    <button type="button" class="fab show-password" onclick="togglePasswordVisibility(this)"></button>
    <br><br>
    <h2>Confirm password</h2>
    <input id="password-confirm" type="password" class="long" name="password_confirmation" placeholder="Confirm password" autocomplete="new-password">
    <button type="button" class="fab show-password" onclick="togglePasswordVisibility(this)"></button>
    <div id="password-strength">Password strength</div>
    <div id="password-still-needs" class="invalid-feedback" role="alert"></div>
</div>

<div class="editor-field">
    <h2>Country of residence</h2>
    <select name="country" autocomplete="off">
        <option value="">Select country</option>
        @foreach($countries as $country)
        <option @if($userCountryCode === $country->code) selected @endif value="{{ $country->code }}">{{ $country->name }}</option>
        @endforeach
    </select>
</div>

<div class="editor-field">
    <h2>Roles</h2>
    <x-multiselect id="roles" name="roles" label="Select role" :options="$roles->pluck('label', 'id')->toArray()" :selected="$userRoleIds"/>
</div>

<div class="editor-field">
    <h2>Countries the user is responsable for</h2>
    <x-multiselect id="responsible_countries" name="responsible_countries" label="Select country" :options="$countries->pluck('name', 'code')->toArray()" :selected="$userResponsableCountries"/>
</div>

<div class="editor-field">
    <h2>Groups the user is responsable for</h2>
    <x-multiselect id="responsible_groups" name="responsible_groups" label="Select groups" :options="$groups" :selected="$userResponsableGroups"/>
</div>

@push('scripts')
<script src="{{ mix('/js/templates/admin/users/scripts.js') }}"></script>
@endpush