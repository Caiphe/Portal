@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/contact/index.css') }}">
@endpush

@section('title', 'Contact us')

@section("content")
    <x-heading heading="Contact us"></x-heading>

    <section>
        <div class="container">
            <div class="column help">
                <h2>Need more help?</h2>

                <p>
                    Want to know about available data, localisation, sandbox, costs and more? So do many others. Check out the answers to some frequently asked questions.
                </p>

                <a class="button after arrow-right" href="{{route('faq.index')}}">View FAQ's</a>
            </div>
            <div class="column contact">
                <h2>Get in touch</h2>

                <x-contact-form></x-contact-form>
            </div>            
        </div>
    </section>
@endsection

@push('scripts')
<script src="{{ mix('/js/templates/contact/index.js') }}" defer></script>
@endpush
