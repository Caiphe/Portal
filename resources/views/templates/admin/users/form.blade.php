@php
    $userRoleId = $user->roles[0]->pivot->role_id ?? 0;
    $userCountryId = $user->countries[0]->pivot->country_id ?? 0;
    $userResponsableCountries = isset($user) ? $user->responsibleCountries()->pluck('id')->toArray() : [];
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
    <input type="password" class="long" name="password" placeholder="Password" autocomplete="off">
    <br><br>
    <h2>Confirm password</h2>
    <input type="password" class="long" name="password_confirmation" placeholder="Confirm password" autocomplete="off">
</div>

<div class="editor-field">
    <h2>Roles</h2>
    <select name="roles" autocomplete="off">
        @foreach($roles as $role)
        <option @if($userRoleId === $role->id || ($userRoleId === 0 && $role->name === 'developer')) selected @endif value="{{ $role->id }}">{{ $role->label }}</option>
        @endforeach
    </select>
</div>

<div class="editor-field">
    <h2>Country of residence</h2>
    <select name="country" autocomplete="off">
        <option value="">Select country</option>
        @foreach($countries as $country)
        <option @if($userCountryId === $country->id) selected @endif value="{{ $country->id }}">{{ $country->name }}</option>
        @endforeach
    </select>
</div>

<div class="editor-field">
    <h2>Countries the user is responsable for</h2>
    <x-multiselect id="responsible_countries" name="responsible_countries" label="None" :options="$countries->pluck('name', 'id')->toArray()" :selected="$userResponsableCountries"/>
</div>