@extends('layouts.auth')

@section('content')
<div class="row m-0">
    <div style="padding: 50px; width: 35vw; height: 100vh; float: left;">
        <x-auth.header/>

        <div >
            <h2>Almost there...</h2>
            <p>
                A confirmation email has been sent to your email address. 
                Please click on the link in the email to verify your email address. <br/><br/>
                If you are not receiving the cnfirmation email, please read out FAQ article here.
            </p>

            <button style="display: inline;">Resend verification</button>
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
