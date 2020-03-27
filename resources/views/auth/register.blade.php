@extends('layouts.auth')

@section('content')
<div class="row m-0">
    <div style="padding: 50px; width: 35vw; height: 100vh; float: left;">
        <div class="row m-0 step__wizzard_header">
            <img style="width: 60px;"src="/images/mtn-logo.svg" alt="MTN logo">
            <h2 style="padding-top: 5px; margin-left: 30px;">Developer Portal</h2>
        </div>

        <div class="step__wizzard_content">
            <div class="step__wizzard_item">
                <p>
                    Already have an account ?
                    <a style="color: #FC0;">Login here &#8594;</a>
                </p>
                <h2 style="font-size: 60px;">Y'ello there!</h2>
                <p>
                    Let’s get you registered and on your way to building some awesome new apps.
                </p>
                <div>
                    <label style="display: block; font-size: 20px;"><strong>What's your first name? *</strong></label>
                    <input type="text" name="firstname" placeholder="First Name" style="border: none; border-radius: 0; border-bottom: 1.5px solid #e6e6e6; width: 100%;"/>
                </div>
                <div>
                    <label style="display: block; font-size: 20px;"><strong>What is your email address? *</strong></label>
                    <input type="email" name="email" placeholder="Email address" style="border: none; border-radius: 0; border-bottom: 1.5px solid #e6e6e6; width: 100%;"/>
                </div>
            </div>

            <div class="step__wizzard_item">
                <div>
                    <label style="display: block; font-size: 20px;"><strong>And your last name, <span id="firstNameSlot"></span>? *</strong></label>
                    <input type="email" name="email" placeholder="Email address" style="border: none; border-radius: 0; border-bottom: 1.5px solid #e6e6e6; width: 100%;"/>
                </div>
            </div>

            <div class="step__wizzard_item">
                <div>
                    <label style="display: block; font-size: 20px;"><strong>And your secret password? *</strong></label>
                    <input type="password" name="password" placeholder="Password" style="border: none; border-radius: 0; border-bottom: 1.5px solid #e6e6e6; width: 100%;"/>
                </div><br/>
                <div>
                    <label style="display: block; font-size: 20px;"><strong>Can you confirm that please, <span id="firstNameSlot"></span>? *</strong></label>
                    <input type="password" name="confirmPass" placeholder="Confirm Password" style="border: none; border-radius: 0; border-bottom: 1.5px solid #e6e6e6; width: 100%;"/>
                    <span style="background-color: #D2D2D2; border-radius: 5px; padding: 5px 10px; margin-top: 10px;">Strong Password</span>
                </div>
            </div>

            <div class="step__wizzard_item">
                <label style="display: block; font-size: 20px;"><strong>In which countries do you intend to release your applications? *</strong></label>
                <p>
                    Some of our APIs are country specific. By specifying specific countries we can help narrow your search for the right APIs.
                </p>
            </div>

            <div class="step__wizzard_item">
                <label style="display: block; font-size: 20px;">Please accept our terms and conditions*</label>
                <p style="background-color: #F3F3F3; overflow-y: scroll; height: 300px; padding: 10px 5px;">
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore.
                </p>
                <div>
                    <x-switch></x-switch> Accept
                </div>
            </div>
        </div>

        <div class="step_wizzard__footer">
            <button id="stepWizzardPrevBtn" onclick="nextPrev(-1)" class="dark outline" style="display: inline;">Back</button>
            <button id="stepWizzardNextBtn" onclick="nextPrev(1)" class="" style="display: inline;">Next</button>
            <p style="display: inline;color: grey;">press Enter &crarr;</p>
        </div>

        <div class="step__wizzard_progress_bar step__wizzard_bar_container"></div>
        <div id="stepWizzardProgress" class="step__wizzard_progress_bar"></div>
    </div>
    <!-- <div class="col-md-8 m-0 p-0" style="background-image: url('/images/carousel-placeholder-img.png'); background-position: center; background-size: contain; padding: 10px; min-height: 100vh;"></div> -->
    <x-carousel style="width: 65vw; height: 100vh; float: left;" wait="5000" duration="0.34">
        <x-carousel-item style="background-image: url('/images/mtn-carousel-img-01.png'); background-position: center; background-size: cover; background-repeat: no-repeat;">
        <div style="position: absolute; left: 20%; margin-left: -50px; bottom: 14%; margin-bottom: -50px; max-width: 800px;">
            <h1 style="color: #fff; font-size: 6em;">Create an account</h1>
            <p style="color: #fff; display: block; font-size: 1.5em; line-height: 1.6em;">
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna.
            </p>
        </div>
        </x-carousel-item>

        <x-carousel-item style="background-image: url('/images/mtn-carousel-img-02.png'); background-position: center; background-size: cover; background-repeat: no-repeat;">
            <div style="position: absolute; left: 20%; margin-left: -50px; bottom: 14%; margin-bottom: -50px; max-width: 800px;">
                <h2 style="color: #fff; font-size: 6em;">Register today!</h2>
                <p style="color: #fff; display: block; font-size: 1.5em; line-height: 1.6em;">
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                    sed diam nonumy eirmod tempor invidunt ut labore et dolore magna.
                </p>
            </div>
        </x-carousel-item>

        <x-carousel-item style="background-image: url('/images/mtn-carousel-img-01.png'); background-position: center; background-size: cover; background-repeat: no-repeat;">
            <div style="position: absolute; left: 20%; margin-left: -50px; bottom: 14%; margin-bottom: -50px; max-width: 800px;">
                <h2 style="color: #fff; font-size: 6em;">Create an account</h2>
                <p style="color: #fff; display: block; font-size: 1.5em; line-height: 1.6em;">
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                    sed diam nonumy eirmod tempor invidunt ut labore et dolore magna.
                </p>
            </div>
        </x-carousel-item>

        <x-carousel-item style="background-image: url('/images/mtn-carousel-img-02.png'); background-position: center; background-size: cover; background-repeat: no-repeat;">
            <div style="position: absolute; left: 20%; margin-left: -50px; bottom: 14%; margin-bottom: -50px; max-width: 800px;">
                <h2 style="color: #fff; font-size: 6em;">Join Us today</h2>
                <p style="color: #fff; display: block; font-size: 1.5em; line-height: 1.6em;">
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
