@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="/css/templates/contact/index.css">
@endpush

@section("content")
    <x-heading heading="Contact Us">
    </x-heading>

    <h1 class="add-pt-69">Need more Help?</h1>
    <p class="add-pt-20">
        <span>Want to know about available data, localisation, sandbox, costs and more? So do many others.</span>
        <span>Check out the answers to some frequentlky asked questions.</span>
    </p>
    <div class="btn add-pt-20">
        <button class="arrow-right after">View FAQ's</button>
    </div>
    <div class="add-pt-80">
        <div class="row bg-color">
            <div class="column">
                <h2>Contact Details</h2>
                <div class="location-icon">
                    <p class="text">
                        <span class="s-left">14th Avenue</span>
                        <span class="s-left">Johannesburg</span>
                        <span class="s-left">2196, South Africa</span>
                    </p>
                </div>
                <div class="column">
                <div class="contact-icon">
                    <p class="text">
                        <span class="s-left">+27 11 912 3000</span>
                    </p>
                </div>
                </div>
            </div>
            <div class="column">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3581.4426301231297!2d27.92883555117222!3d-26.149710368030952!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1e950aa4037c8d6b%3A0x5e0ccf872377c77e!2sMTN!5e0!3m2!1sen!2sza!4v1585650125707!5m2!1sen!2sza" width="100%" height="427" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
        </div>
    </div>
    
    <h1 class="add-pt-80">Need more Help?</h1>
    <p class="add-pt-20">
        <span>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</span>
        <span>nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam.</span>
    </p>

@endsection