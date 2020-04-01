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
                    <h2 class="header">Almost there…</h2>
                    <p class="text">
                        A confirmation email has been sent to your email address.
                        Please click on the link in the email to verify your email address.
                    </p>
                    <p>
                        If you are not receiving the cnfirmation email, please read out FAQ article <a href="/faq" style="text-decoration: none; color: #FC0;">here</a>.
                    </p>
                </div>
                <div class="login__input_group">
                    <button type="submit">{{ __('Resend verification') }}</button>
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