@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet" href="/css/components/step-wizzard.css">
@endpush

@section('content')
<div class="row m-0">
    <div class="step__wizzard_container left">
        <div class="row m-0 step__wizzard_header">
            <img src="/images/mtn-logo.svg" alt="MTN logo">
            <h2>Developer Portal</h2>
        </div>

        <form class="step__wizzard_content" id="stepWizzardForm" method="POST" action="{{ route('register') }}">
            @csrf
            <div class="step__wizzard_item">
                <div class="intro">
                    <p class="intro_return_user">
                        Already have an account ?
                        <a class="login_link" href="/login" >Login here &#8594;</a>
                    </p>
                    <h2 class="intro_header">Y'ello there!</h2>
                    <p class="intro_text">
                        Let’s get you registered and on your way to building some awesome new apps.
                    </p>
                </div>

                <div class="input_group">
                    <label for="firstname"><strong>What's your first name? *</strong></label>
                    <input class="@error('firstname') is-invalid @enderror" type="text" id="formFirstName" name="firstname" value="{{ old('firstname') }}" required autocomplete="firstname" placeholder="First Name" autofocus />
                    @error('firstname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="input_group">
                    <label for="email"><strong>What is your email address? *</strong></label>
                    <input class="@error('email') is-invalid @enderror" type="email" id="formEmail" name="email" value="{{ old('email') }}" required autocomplete="off" placeholder="Email Address" />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="step__wizzard_item">
                <div class="item_content">
                    <div class="input_group">
                        <label><strong>And your last name, <span class="first__name_slot"></span>? *</strong></label>
                        <input style="width: 100%;" class="@error('lastname') is-invalid @enderror" type="text" id="formLastName" name="lastname" value="{{ old('lastname') }}" required autocomplete="off" placeholder="Last name" />
                        @error('lastname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="step__wizzard_item">
                <div class="input_group">
                    <label><strong>And your secret password? *</strong></label>
                    <input class="@error('password') is-invalid @enderror" type="password" id="formPassword" name="password" value="{{ old('password') }}" required autocomplete="off" placeholder="Password" />
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="input_group">
                    <label><strong>Can you confirm that please, <span class="first__name_slot"></span>? *</strong></label>
                    <input type="password" id="formPasswordConf" name="confirmPass" value="{{ old('confirmPass') }}" required autocomplete="off" placeholder="Confirm Password" />
                </div>
                <div>
                    <span class="password_strength grey">Strong Password</span>
                </div>
            </div>

            <div class="step__wizzard_item">
                <label class="step-input-label"><strong>In which countries do you intend to release your applications? *</strong></label>
                <p>
                    Some of our APIs are country specific. By specifying specific countries we can help narrow your search for the right APIs.
                </p>
                <div class="input_group location_container">
                    <div class="location_select_item untouched" id="formLocationOptions" onclick="selectLocation(event)">
                        <img data-value="za" src="/images/locations/za.svg" />
                        <img data-value="af" src="/images/locations/af.svg" />
                        <img data-value="bj" src="/images/locations/bj.svg" />
                        <img data-value="bw" src="/images/locations/bw.svg" />
                        <img data-value="cm" src="/images/locations/cm.svg" />

                        <img data-value="gh" src="/images/locations/gh.svg" />
                        <img data-value="gw" src="/images/locations/gw.svg" />
                        <img data-value="gn" src="/images/locations/gn.svg" />
                        <img data-value="ir" src="/images/locations/ir.svg" />
                        <img data-value="ci" src="/images/locations/ci.svg" />

                        <img data-value="lr" src="/images/locations/lr.svg" />
                        <img data-value="na" src="/images/locations/na.svg" />
                        <img data-value="ng" src="/images/locations/ng.svg" />
                        <img data-value="cg" src="/images/locations/cg.svg" />
                        <img data-value="rw" src="/images/locations/rw.svg" />

                        <img data-value="sd" src="/images/locations/sd.svg" />
                        <img data-value="sz" src="/images/locations/sz.svg" />
                        <img data-value="sy" src="/images/locations/sy.svg" />
                        <img data-value="ug" src="/images/locations/ug.svg" />
                        <img data-value="ye" src="/images/locations/ye.svg" />
                    </div>
                </div>
                <p id="locationsErrorText" class="locations_error_text"><i>Please select a location!</i></p>
            </div>

            <div class="step__wizzard_item">
                <label class="step-input-label">Please accept our terms and conditions*</label>
                <p class="terms_and_conditions">
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore.
                </p>
                <div>
                    <x-switch></x-switch> Accept
                </div>
            </div>
        </form>

        <div class="step_wizzard__footer">
            <button id="stepWizzardPrevBtn" onclick="nextPrev(-1)" class="dark outline">Back</button>
            <button id="stepWizzardNextBtn" onclick="nextPrev(1)" >Next</button>
            <p>
                press Enter &crarr;
                <input id="keyUpListner" name="keyUpListner"/>
            </p>
        </div>

        <div class="step__wizzard_progress_bar step__wizzard_bar_container"></div>
        <div id="stepWizzardProgress" class="step__wizzard_progress_bar"></div>
    </div>
    
    <x-carousel class="step__wizzard_container right" wait="5000" duration="0.34">
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

@push('scripts')
    <script src="/js/register.js"></script>
@endpush
