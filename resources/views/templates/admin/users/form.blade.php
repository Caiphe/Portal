@php
    $currentUser = auth()->user();
    $userResponsibleCountries = $currentUser->responsibleCountries()->pluck('code')->toArray();
    $isAdminUser = $currentUser->hasRole('admin');
    $defaultUserRole = [2 => 'Developer'];
@endphp

@csrf

<div class="editor-field">
    <h2>First name</h2>
    <input type="text" class="long" name="first_name" placeholder="First name" value="{{ old('first_name') }}" autocomplete="off" maxlength="140">
</div>

<div class="editor-field">
    <h2>Last name</h2>
    <input type="text" class="long" name="last_name" placeholder="Last name" value="{{ old('last_name') }}" autocomplete="off" maxlength="140">
</div>

<div class="editor-field">
    <h2>Email</h2>
    <input type="text" class="long" name="email" placeholder="Email" value="{{ old('email') }}" autocomplete="off" maxlength="140">
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
            @if(!in_array($country->code, $userResponsibleCountries) && !$isAdminUser) @continue @endif
        <option value="{{ $country->code }}">{{ $country->name }}</option>
        @endforeach
    </select>
</div>

@if($isAdminUser)
<div class="editor-field">
    <h2>Roles</h2>
    <x-multiselect id="roles" name="roles" label="Select role" :options="$roles->pluck('label', 'id')->toArray()" :selected="$defaultUserRole"/>
</div>

<div class="editor-field">
    <h2>Countries the user is responsible for</h2>
    <x-multiselect id="responsible_countries" name="responsible_countries" label="Select country" :options="$countries->pluck('name', 'code')->toArray()"/>
</div>
@endif

<div class="editor-field">
    <h2>Groups the user is responsible for</h2>
    <x-multiselect id="responsible_groups" name="responsible_groups" label="Select groups" :options="$groups"/>
</div>

@push('scripts')
<script src="{{ mix('/js/templates/admin/users/scripts.js') }}"></script>
@endpush
