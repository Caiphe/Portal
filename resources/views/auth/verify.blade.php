@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet" href="/css/auth/step-wizard.css">
@endpush

@section('content')
	<div class="step__wizard_container left">
		<x-auth.header/>
		<form class="step__wizard_content" id="stepwizardForm" method="POST" action="{{ route('register') }}">
			@csrf
			<div class="intro">
				<h2 class="header">Almost there…</h2>
				<p class="text">
					A confirmation email has been sent to your email address.
					Please click on the link in the email to verify your email address.
				</p>
				<p>
					If you are not receiving the confirmation email, please read our FAQ article <a href="/faq" class="login_link">here</a>.
				</p>
			</div>
			<div class="login__input_group">
				<button type="submit">{{ __('Resend verification') }}</button>
			</div>
		</form>
	</div>
	<x-auth.carousel />
 endsection