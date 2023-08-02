@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet" href="{{mix('/css/auth/forgot-password.css')}}">
@endpush

@php
	$errs = $errors->get('email');
	$emailNotValid = in_array(__('passwords.user'), $errs);
	$errs = array_diff($errs, [__('passwords.user')]);
	$hasEmailErrors = !empty($errs);
@endphp

@section('title', 'Forgot password?')

@section('content')
	<x-auth.header/>
	<form method="POST" action="{{ route('password.email') }}">
		@csrf
		<h1 class="t-large">Did you forget something…</h1>

		@if (session('status') || $emailNotValid)
	        <div class="forgot-password-success" role="alert">
	            {{ __('passwords.sent') }}
	        </div>
	    @endif

		<p>Please supply your email address and we'll send you a reset email.</p>
		<input id="email" type="email" class="@if($hasEmailErrors) invalid @endif" name="email" value="" placeholder="Email address" required autocomplete="email" autofocus>

		@if($hasEmailErrors)
			<span class="invalid-feedback" role="alert">
				@foreach ($errs as $err)
					<strong>{{ $err }}</strong> <br />
				@endforeach
			</span>
		@endif

		<button type="submit" class="inline submit-request">
			{{ __('Remind me') }}
		</button>
	</form>
	<a class="try-somewhere-else yellow bottom t-small" href="{{route('login')}}"><span>Not the right place?</span> Click here to log in @svg('arrow-forward', '#fc0')</a></div>
    <x-auth.carousel />

@endsection