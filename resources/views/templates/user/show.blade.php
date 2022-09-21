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
                [ 'label' => 'My profile', 'link' => '/profile'],
                [ 'label' => '2FA', 'link' => '#twofa'],
                [ 'label' => 'My apps', 'link' => '/apps'],
                [ 'label' => 'My teams', 'link' => '/teams'],
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
    <x-heading heading="My profile"/>

    <!--- Add team invite banner -->

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
            <input type="text" name="first_name" value="{{ old('first_name') ?: $user['first_name'] }}" placeholder="First name" autocomplete="first_name">
            <input type="text" name="last_name" value="{{ old('last_name') ?: $user['last_name'] }}" placeholder="Last name" autocomplete="last_name">
            <input type="email" name="email" value="{{ old('email') ?: $user['email'] }}" placeholder="Email" autocomplete="email">
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
            <h2>2FA settings</h2>
            <h3>Add to authenticator app</h3>

            {!! $inlineUrl !!}

            <h4>Key: {{ $key }}</h4>
            @csrf
            <input type="hidden" name="one_time_key" value="{{ $key }}">
            <input id="authenticator-code" type="text" name="one_time_password" placeholder="Authenticator code" required autocomplete="off">
            <button>Authenticate</button>
        </form>
        @else
        <form id="twofa" class="centre pt-4" action="{{ route('user.2fa.disable') }}" method="POST">
            <h2>2FA settings</h2>
            {!! $inlineUrl !!}

            <h4>Key: {{ $key }}</h4>

            @csrf
            <button class="button outline dark twofa-button">Disable 2FA</button>

            <h2>Recovery codes</h2>
            <p class="align-left">Recovery codes are a way to be able to log into your account if you have lost access to your Authenticator App.</p>
            <p class="align-left">Each recovery code can only be used once. When you run out of recovery codes, you can come back here and generate more codes.</p>
            <button id="recovery-codes" type="button">View recovery codes</button>

            <div id="show-recovery-codes"></div>
        </form>
        @endisset

        {{-- Opco Admin Role request by a developer --}}
        @if(!in_array('Admin', $userRoles))
            @can('request-opco-admin-role')
                <form class="opco-role-request-form" id="opco-role-request-form" method="POST" action="{{ route('opco-admin-role.store') }}">
                    @csrf
                    <h2>Apply for Admin Role</h2>
                    <p class="align-left">
                        If you would like to request the addition of the OpCo Admin Role to your profile, please supply a motivation below why you should be granted this role, including the applicable countries you are requesting permission for.
                    </p>

                    <p>
                        Your application will be received by existing Admins, assigned to the countries you have and will be reviewed accordingly.
                    </p>

                    <h2>Motivation for Admin Role</h2>
                    <textarea class="" rows="4" name="message" placeholder='Please motivate the reason for your request of the OpCo Admin role e.g.  "Applying for the OpCo administrative role as I have been promoted to team lead of country X. "'></textarea>
                    <h2>Your selected countries</h2>
                    
                    <div class="locations">
                        
                        @foreach($locations as $location)
                        <label for="user-{{ $location }}">
                            <input type="checkbox" value="{{ $location }}" id="user-{{ $location }}" autocomplete="off" @if(in_array($location, $responsibleCountries)) name="locations[]" checked @else name="countries[]" @endif>
                            <img src="/images/locations/{{ $location }}.svg" alt="{{ $location }}" title="{{ $location }}">
                        </label>
                        @endforeach

                    </div>
                    <button type="submit" class="primary">Apply Now</button>
                </form>
            @endcan
        @endif

    </div>
@endsection

@push('scripts')
<script src="{{ mix('/js/templates/user/show.js') }}"></script>
@endpush
