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

            <form method="POST" action="{{ route('password.email') }}" style="margin-top: 40%;">
                @csrf
                <div class="intro">
                    <h2 class="header" style="font-size: 40px; line-height: 40px;">Did you forget something…</h2>
                    <p class="text">
                        Please supply your email addres and we’ll send you a reset email.
                    </p>
                </div>
                <div class="form-group row">
                    <div class="login__input_group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email Address" required autocomplete="email" autofocus>
                        <p>
                            press Enter &crarr;
                            <input id="keyUpListner" name="keyUpListner"/>
                        </p>
                        @error('email')
                            <span style="color: #FC0;" class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Remind me') }}
                    </button>
                </div>
            </form>
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