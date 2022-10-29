@extends('layouts.master')


@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/2fa/index.css') }}">
@endpush

@section('content')
<div class="twofa-main-container">
    <div class="inner-top-twofa">
        <img src="/images/illustrations/2fa.svg" class="twofa-icon" alt="2FA Illustration" />

        <form id="form-2fa" class="form-2fa" action="{{ route('user.2fa.verify') }}" method="POST">
            @csrf
            <input name="one_time_password" type="text" placeholder="Enter authenticator code" required autocomplete="off" autofocus>
            <button type="submit">Authenticate</button>
        </form>

        <button type="button" class="reset-twofa" id="reset-twofa" href="">Can't access your authentification device?</button>

        {{-- Reset twofa --}}
        <div class="reset-container" id="reset-container">
            <h4>Reset your two factor authentication</h4>
            <p>Once confirming your request to reset your 2FA, an administrator will be able to complete your request</p>

            
            <form class="reset-form" id="reset-form" action="{{ route('2fa.reset.request', auth()->user()) }}">
                @csrf
                <input type="hidden" name="user" value="{{ auth()->user()->id }}" />
                <div class="confirm-checkbox">
                    <input type="checkbox" id="confirm" class="confirm-check" value="confirm" autocomplete="off">
                    <label class="confirm-label" for="confirm">
                        I confirm I have lost my device with my 2FA authenticator, or am otherwise unable to access my account
                    </label>
                </div>

                <button type="submit" id="reset-btn" class="reset-btn non-active">Confirm reset request</button>
            </form>
        </div>

    </div>

    {{-- Request complete --}}
    <div class="complete-request" id="complete-request">
        <div class="inner-container">
            <h4>Authentication reset request complete</h4>
            <p>Please check your email and spam folder for confirmation of your 2FA reset.</p>

            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button class="button continue-btn">Continue</button>
            </form>
        </div>
        
        <p class="gray-text">Consider downloading your 2FA recovery codes under you profile section when logging into your account. Recovery codes can be used to access your account should you lose your 2FA device and authenticator</p>
    </div>

</div>
@endsection

@push('scripts')
    <script src="{{ mix('/js/templates/2fa/index.js') }}" defer></script>
@endpush
