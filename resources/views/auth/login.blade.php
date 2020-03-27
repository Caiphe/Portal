@extends('layouts.auth')

@section('content')
<div class="row m-0">
    <div style="padding: 50px; width: 35vw; height: 100vh; float: left;">
        <div class="row m-0 step__wizzard_header">
            <img style="width: 60px;"src="/images/mtn-logo.svg" alt="MTN logo">
            <h2 style="padding-top: 5px; margin-left: 30px;">Developer Portal</h2>
        </div>

        <div >
            <h2>You're in!</h2>
            <p>
                Thank you for verifying your email address, you’re all setup. Happy coding!
            </p>

            <input style="display: block;" type="text" name="username" placeholder="Username" />
            <input style="display: block;" type="password" name="password" placeholder="Password" />

            <button style="display: inline;">Log in</button>
            <p style="display: inline;color: grey;">press Enter &crarr;</p>
        </div>
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