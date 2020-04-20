@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/contact/index.css') }}">
@endpush

@section('title', 'Contact us')

@section("content")
    <x-heading heading="Contact us"></x-heading>

    <section>
        <div class="container">
            <x-action-tab
                link="https://spectrum.chat/mtn-developer-hub"
                text="For more help, connect with us on Spectrum"
                logo="spectrum">
            </x-action-tab>

            <x-action-tab
                link="https://madapi.statuspage.io/"
                title="Network status."
                text="See more on our status page"
                status="green">
            </x-action-tab>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="column">
                <h2>Need more help?</h2>

                <p>
                    Want to know about available data, localisation, sandbox, costs and more? So do many others. Check out the answers to some frequentlky asked questions.
                </p>

                <button>
                    View FAQ's
                    @svg('arrow-forward')
                </button>
            </div>
            <div class="column">
                <h2>Get in touch</h2>

                <x-contact-form></x-contact-form>
            </div>
            <div class="column">
                <h2>Contact details</h2>
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
        </div>
    </section>

{{--    <section class="grey-bg mb-4">--}}
{{--		<div class="columns">--}}
{{--			<div class="column contact-details">--}}
{{--				<h3>Contact Details</h3>--}}
{{--                <address>--}}
{{--                    <div class="left">--}}
{{--                        @svg('map-marker', '#FC0')--}}
{{--                        <div>--}}
{{--                            <span>14th Avenue</span>--}}
{{--                            <span>Johannesburg</span>--}}
{{--                            <span>2196, South Africa</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="right">--}}
{{--                        @svg('phone', '#FC0')--}}
{{--                        <a href="tel:+27 11 912 3000">+27 11 912 3000</a>--}}
{{--                    </div>--}}
{{--                </address>--}}
{{--			</div>--}}
{{--		</div>--}}
{{--	</section>--}}

@endsection
