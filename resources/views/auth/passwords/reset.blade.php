@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet" href="{{mix('/css/auth/forgot-password.css')}}">
@endpush

@section('title', 'Reset password?')

@section('content')
	<x-auth.header/>
	<form id="forgot-password-form" method="POST" action="{{ route('password.update') }}">
        @csrf

        <h1 class="t-large">Almost there...</h1>

        @if (session('type'))
        <p>Sorry for this extra step. We have moved over to a new, better portal and for your security need you to choose a new password</p>
        <p>An email has also been sent in case you would like to continue the journey later.</p>
        @endif

        <input type="hidden" name="token" value="{{ $token }}">

        <input id="email" type="email" class="@error('email') invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" placeholder="Email" autofocus>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <div class="password-section">
            <input id="password" type="password" class="form-control @error('password') invalid @enderror" name="password" placeholder="New password" required autocomplete="new-password">
            <button type="button" class="fab show-password" onclick="togglePasswordVisibility(this)"></button>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="password-section">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm password" autocomplete="new-password">
            <button type="button" class="fab show-password" onclick="togglePasswordVisibility(this)"></button>
        </div>

        <div id="password-strength">Password strength</div>
        <div id="password-still-needs" class="invalid-feedback" role="alert"></div>

        <button type="submit" class="inline">
            {{ __('Reset Password') }}
        </button>
    </form>
    <a class="try-somewhere-else yellow bottom t-small" href="{{route('login')}}"><span>Not the right place?</span> Click here to log in @svg('arrow-forward', '#fc0')</a></div>
    <x-auth.carousel />
@endsection

@push('scripts')
<script src="{{ mix('/js/templates/auth/forgot-password.js') }}"></script>
@endpush