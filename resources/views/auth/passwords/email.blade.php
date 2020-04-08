@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet" href="{{mix('/css/auth/forgot-password.css')}}">
@endpush

@section('title', 'Forgot password?')

@section('content')
	<x-auth.header/>
	<form method="POST" action="{{ route('password.email') }}">
		@csrf
		<h1 class="t-large">Did you forget something…</h1>
		@if (session('status'))
	        <div class="forgot-password-success" role="alert">
	            {{ session('status') }}
	        </div>
	    @endif
		<p>Please supply your email address and we’ll send you a reset email.</p>
		<input id="email" type="email" class="@error('email') invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email Address" required autocomplete="email" autofocus>
		@error('email')
			<span class="invalid-feedback" role="alert">
				<strong>{{ $message }}</strong>
			</span>
		@enderror
		<button type="submit" class="inline">
			{{ __('Remind me') }}
		</button>
	</form>
	<a class="try-somewhere-else yellow bottom t-small" href="{{route('login')}}"><span>Not the right place?</span> Click here to log in @svg('arrow-forward', '#fc0')</a></div>
    <x-auth.carousel />
@endsection