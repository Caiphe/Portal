@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet" href="/css/auth/step-wizard.css">
@endpush

@section('title','Login')

@section('content')
	<div class="step__wizard_container left">
		<x-auth.header/>
		<form class="step__wizard_content" id="stepwizardForm" method="POST" action="{{ route('login') }}">
			@csrf
			<div class="intro">
				<h2 class="header">Login</h2>
			</div>
			<div class="login__input_group">
				<input class="@error('email') is-invalid @enderror" type="text" id="formEmail" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email address" autofocus />
				@error('email')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
				@enderror
			</div>
			<div class="login__input_group">
				<input class="@error('password') is-invalid @enderror" type="password" id="formPassword" name="password" value="{{ old('password ') }}" required autocomplete="password" placeholder="Password" />
				@error('password')
					<span class="invalid-feedback" role="alert" style="margin-bottom: 10px;">
						<strong>{{ $message }}</strong>
					</span>
				@enderror
			</div>
			<div class="step_wizard_button_group">
				<button type="submit" class="arrow-right after">
					Log in
				</button>
				@if (Route::has('password.request'))
					<a class="forgot-password-link" href="{{ route('password.request') }}">
						Forgot Password @svg('arrow-forward', '#fc0')
					</a>
				@endif
			</div>
	</div>
	<x-auth.carousel />
@endsection