@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/contact/index.css') }}">
@endpush

@section('title', 'Contact us')

@section("content")
    <x-heading heading="Contact us"></x-heading>

	<section class="faqs-section section-padding mt-4 mb-4">
		<h1>Need more Help?</h1>
		<p> <span>Want to know about available data, localisation, sandbox, costs and more? So do many others.</span>
		<span>Check out the answers to some frequently asked questions.</span></p>
		<a class="button arrow-right after" href="/faq" role="button">View FAQ's</a>
	</section>

    <section class="contact-details-section grey-bg mb-4 section-padding">
		<div class="columns">
			<div class="column contact-details">
				<h3>Contact Details</h3>
                <address>
                    <div class="left">
                        @svg('map-marker', '#FC0')
                        <div>
                            <span>14th Avenue</span>
                            <span>Johannesburg</span>
                            <span>2196, South Africa</span>
                        </div>
                    </div>
                    <div class="right">
                        @svg('phone', '#FC0')
                        <a href="tel:+27 11 912 3000">+27 11 912 3000</a>
                    </div>
                </address>
			</div>
			<div class="column">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3581.4426301231297!2d27.92883555117222!3d-26.149710368030952!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1e950aa4037c8d6b%3A0x5e0ccf872377c77e!2sMTN!5e0!3m2!1sen!2sza!4v1585650125707!5m2!1sen!2sza" width="100%" height="427" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
			</div>
		</div>
	</section>

    <section class="contact-form section-padding">
		<x-contact-form title="Need more help? Get in touch"/>
	</section>

@endsection
