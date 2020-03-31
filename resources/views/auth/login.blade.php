@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet" href="/css/components/step-wizzard.css">
@endpush


@section('content')
    <div class="m-0">
        <div class="step__wizzard_container left">
            <div class="row m-0 step__wizzard_header">
                <img src="/images/mtn-logo.svg" alt="MTN logo">
                <h4>Developer Portal</h4>
            </div>

            <form class="step__wizzard_content" id="stepWizzardForm" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="intro">
                    <h2 class="header">You're in!</h2>
                    <p class="text">
                        Thank you for verifying your email address, you’re all setup. <strong>Happy coding!</strong>
                    </p>
                </div>
                <div class="login__input_group">
                    <input class="@error('username') is-invalid @enderror" type="text" id="formEmail" name="email" value="{{ old('username') }}" required autocomplete="username" placeholder="Username" autofocus />
                    @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="login__input_group">
                    <input class="@error('password') is-invalid @enderror" type="text" id="formPassword" name="password" value="{{ old('password ') }}" required autocomplete="password" placeholder="Password" />
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="step_wizard_button_group">
                    <button type="submit" class="btn btn-primary">
                        Log in &#8594;
                    </button>
                    @if (Route::has('password.request'))
                        <a type="button" class="btn" href="{{ route('password.request') }}">
                            Forgot Password &#8594;
                        </a>
                    @endif
                </div>
        </div>
        <x-carousel class="step__wizzard_container right" wait="5000" duration="0.34">
            <x-carousel-item class="carousel_item_cnt" style="background-image: url('/images/mtn-carousel-img-01.png');">
                <div class="overlay">
                    <h2>Create an account</h2>
                    <p>
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna.
                    </p>
                </div>
            </x-carousel-item>

            <x-carousel-item class="carousel_item_cnt" style="background-image: url('/images/mtn-carousel-img-02.png');">
                <div>
                    <h2>Register today!</h2>
                    <p>
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna.
                    </p>
                </div>
            </x-carousel-item>

            <x-carousel-item class="carousel_item_cnt" style="background-image: url('/images/mtn-carousel-img-01.png');">
                <div>
                    <h2>Create an account</h2>
                    <p>
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna.
                    </p>
                </div>
            </x-carousel-item>

            <x-carousel-item class="carousel_item_cnt" style="background-image: url('/images/mtn-carousel-img-02.png');">
                <div>
                    <h2>Join Us today</h2>
                    <p>
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna.
                    </p>
                </div>
            </x-carousel-item>
        </x-carousel>
    </div>
@endsection