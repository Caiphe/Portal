@extends('layouts.sidebar')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/user/show.css') }}">
@endpush

@section('title')
Update profile
@endsection

@section('sidebar')
    <x-sidebar-accordion id="sidebar-accordion" :active="'/' . request()->path()"
        :list="
        [
            'MANAGE' =>
            [
                [ 'label' => 'Profile', 'link' => '/profile'],
                [ 'label' => '2FA', 'link' => '#twofa'],
                [ 'label' => 'My apps', 'link' => '/apps'],
            ],
            'DISCOVER' =>
            [
                [ 'label' => 'Browse all our products', 'link' => '/products'],
                [ 'label' => 'Working with our products', 'link' => '/getting-started']
            ]
        ]"
    />
@endsection

@section("content")
    <x-heading heading="Profile"/>

    <x-twofa-warning class="tall"></x-twofa-warning>

    <div class="content" id="profile">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <label id="profile-picture-label" for="profile-picture" style="background-image: url({{$user['profile_picture']}});">
            <input type="file" name="profile-picture" id="profile-picture" accept="image/*">
        </label>

        <form action="{{route('user.profile.update')}}" id="profile-form" method="POST">
            @csrf
            @method('put')
            <h2>Personal details</h2>
            <input type="text" name="first_name" value="{{$user['first_name']}}" placeholder="First name" autocomplete="first_name">
            <input type="text" name="last_name" value="{{$user['last_name']}}" placeholder="Last name" autocomplete="last_name">
            <input type="email" name="email" value="{{$user['email']}}" placeholder="Email" autocomplete="email">
            <small class="email-warning">*If you change your email, it will need to be verified again.</small>
            <h2>Password</h2>
            <label class="password-label" for="password">
                <input type="password" name="password" id="password" placeholder="Password" autocomplete="new-password">
                <button type="button" class="fab show-password" onclick="togglePasswordVisibility(this)"></button>
            </label>
            <label class="password-label" for="passwordConfirmation">
                <input type="password" name="password_confirmation" id="passwordConfirmation" placeholder="Confirm password" autocomplete="new-password">
                <button type="button" class="fab show-password" onclick="togglePasswordVisibility(this)"></button>
            </label>
            <div id="password-strength">Password strength</div>
            <div id="password-still-needs" role="alert"></div>
            <h2>Your selected countries</h2>
            <div class="locations">
                @foreach($locations as $location)
                <label for="{{$location}}">
                    <input type="checkbox" name="locations[]" value="{{$location}}" id="{{$location}}" autocomplete="off" @if(in_array($location, $userLocations)) checked @endif>
                    <img src="/images/locations/{{$location}}.svg" alt="{{$location}}" title="{{$location}}">
                </label>
                @endforeach
            </div>
            <button class="dark">Save</button>
        </form>

        @if(empty($user['2fa']))
        <form id="twofa" class="enable-2fa" action="{{ route('user.2fa.verify') }}" method="POST">
            <h2>Enable 2FA</h2>
            <h3>Add to authenticator app</h3>

            <h4>Key: {{ $key }}</h4>

            {!! $inlineUrl !!}
            @csrf
            <input type="hidden" name="one_time_key" value="{{ $key }}">
            <input id="authenticator-code" type="text" name="one_time_password" placeholder="Authenticator code" required autocomplete="off">
            <button>Authenticate</button>
        </form>
        @else
        <form id="twofa" class="centre pt-4" action="{{ route('user.2fa.disable') }}" method="POST">
            <h4>Key: {{ $key }}</h4>

            {!! $inlineUrl !!}

            @csrf
            <button class="button outline dark twofa-button">Disable 2FA</button>

            <h2>Recovery codes</h2>
            <p>Recovery codes are a way to be able to log into your account if you have lost access to your Authenticator App.</p>
            <p>Each recovery code can only be used once. When you run out of recovery codes, you can come back here and generate more codes.</p>
            <button id="recovery-codes" type="button">View recovery codes</button>

            <div id="show-recovery-codes"></div>
        </form>
        @endisset
    </div>
@endsection

@push('scripts')
<script src="{{ mix('/js/templates/user/show.js') }}"></script>
@endpush
