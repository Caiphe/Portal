@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/auth/register.css') }}">
@endpush

@section('title','Register')

@section('content')
    <x-auth.header/>
    <form id="register-form" method="POST" class="current-section-0" action="{{ route('register') }}">
        @csrf
        <section class="section-0">
            <a class="try-somewhere-else yellow t-small" href="{{route('login')}}"><span>Already have an account?</span> Log in here @svg('arrow-forward', '#fc0')</a></div>
            <h1 class="t-large">Y'ello there!</h1>
            <p>Let's get you registered and on your way to building some awesome new apps.</p>
            <label for="first-name">What's your first name? *</label>
            <input type="text" name="first_name" id="first-name" class="alt @error('name') invalid @enderror" value="{{ old('first_name') }}" required placeholder="First name" autocomplete="first_name" autofocus>
            <span class="invalid-feedback" role="alert">@error('first_name') {{ $message }} @enderror</span>
            <label for="email">What is your email address? *</label>
            <input id="email" type="email" class="alt @error('email') invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Email" autocomplete="username">
            <span class="invalid-feedback" role="alert">@error('email') {{ $message }} @enderror</span>
        </section>

        <section class="section-1">
            <label for="last-name">And your last name, <span class="entered-name"></span>? *</label>
            <input id="last-name" type="text" class="alt @error('last_name') invalid @enderror" name="last_name" value="{{ old('last_name') }}" required placeholder="Last name" autocomplete="last_name">
            <span class="invalid-feedback" role="alert">@error('last_name') {{ $message }} @enderror</span>
        </section>

        <section class="section-2">
            <label for="password">And your secret password? *</label>
            <input id="password" type="password" class="alt @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="new-password">
            <button type="button" class="fab show-password" onclick="togglePasswordVisibility(this)"></button>
            <span class="invalid-feedback" role="alert">@error('password') {{ $message }} @enderror</span>
            <label for="password">Can you confirm that please, <span class="entered-name"></span>? *</label>
            <input id="password-confirm" type="password" class="alt" name="password_confirmation" placeholder="Confirm password" required autocomplete="new-password">
            <button type="button" class="fab show-password" onclick="togglePasswordVisibility(this)"></button>
            <span class="invalid-feedback" role="alert"></span>
            <div id="password-strength">Password strength</div>
            <div id="password-still-needs" class="invalid-feedback" role="alert"></div>
        </section>

        <section class="section-3">
            <h3>In which countries do you intend to release your applications? *</h3>
            <p>Some of our APIs are country specific. By specifying specific countries we can help narrow your search for the right APIs.</p>
            <div class="locations">
                @foreach($locations as $code => $name)
                <label for="{{$code}}">
                    <input type="checkbox" name="locations[]" value="{{$code}}" id="{{$code}}" autocomplete="off">
                    <img src="/images/locations/{{$code}}.svg" alt="{{$name}}" title="{{$name}}">
                </label>
                @endforeach
            </div>
        </section>

        <section class="section-4">
            <h3>Please accept our terms and conditions *</h3>
            <div class="terms">{!!$terms!!}</div>
            <x-switch id="terms" name="terms" value="accept" class="mt-2">Accept</x-switch>
            <span id="terms-invalid-feedback" class="invalid-feedback" role="alert"></span>
        </section>

        <div class="controls">
            <button id="back" type="button" class="dark outline before arrow-left">Back</button>
            <button id="next" type="button" class="after arrow-right">Next</button>
            <button id="create-new-account" type="button">Create new account</button>
            <div id="enter" class="t-small">or press <strong>enter</strong> @svg('enter', '#999')</div>
        </div>
        <div class="progress">
            <div class="progressed"></div>
        </div>
    </form>
    <x-auth.carousel />
@endsection

@push('scripts')
    <script src="{{ mix('/js/templates/auth/register.js') }}"></script>
@endpush
