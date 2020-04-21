@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/auth/verify.css') }}">
@endpush

@section('title','Verify email')

@section('content')
<x-auth.header/>
<form method="POST" action="{{ route('verification.resend') }}">
    @csrf
	<h1>Almost there...</h1>
    @if (session('resent'))
    <div class="resent-success" role="alert">
        A fresh verification link has been sent to your email address.
    </div>
    @endif
	<p>A confirmation email has been sent to your email address. Please click on the link in the email to verify your email address.</p>
	<p>If you are not receiving the confirmation email, please read out FAQ article <a href="/faq">here.</a></p>
    <button type="submit">Resend verification</button>
</form>
<x-auth.carousel />
@endsection