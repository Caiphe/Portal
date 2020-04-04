@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet" href="/css/auth/step-wizard.css">
@endpush

@section('title', 'Forgot password?')

@section('content')
	<div class="step__wizard_container left">
		<x-auth.header/>

		<form method="POST" action="{{ route('password.email') }}" class="step__wizard_content">
			@csrf
			<div class="intro">
				<h2 class="header">Did you forget something…</h2>
				<p>
					Please supply your email address and we’ll send you a reset email.
				</p>
			</div>
			<div class="form-group row">
				<div class="login__input_group">
					<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email Address" required autocomplete="email" autofocus>
					@error('email')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>
			</div>
			<div class="step_wizard_button_group">
				<button type="submit">
					{{ __('Remind me') }}
				</button>
			</div>
		</form>
	</div>
    <x-auth.carousel />
@endsection