@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet" href="{{mix('/css/auth/login.css')}}">
@endpush

@section('title','Login')

@section('content')
	<x-auth.header/>
	<form method="POST" class="login-form" action="{{ route('login') }}">
		@csrf
		<input class="@error('email') invalid @enderror" type="text" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email address" autofocus />
		@error('email')
			<span class="invalid-feedback" role="alert">
				<strong>{{ $message }}</strong>
			</span>
		@enderror
		<input class="@error('password') invalid @enderror" type="password" id="password" name="password" value="{{ old('password ') }}" required autocomplete="password" placeholder="Password" />
		@error('password')
			<span class="invalid-feedback mt-1" role="alert">
				<strong>{{ $message }}</strong>
			</span>
		@enderror
		<div class="inline">
			<button type="submit" class="arrow-right after mr-1">Log in</button>
			<a class="forgot-password-link yellow" href="{{ route('password.request') }}">
				Forgot Password @svg('arrow-forward', '#fc0')
			</a>
		</div>
	</form>
	<x-auth.carousel />
@endsection