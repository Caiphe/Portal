@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/faq/index.css') }}">
@endpush

@section('title','FAQ')

@section('content')
	<x-heading heading="FAQ"></x-heading>

    <section>
        <div class="container">
            <x-action-tab text="For more help, connect with us on Spectrum" logo="spectrum"></x-action-tab>

            <x-action-tab title="Network status." text="See more on our status page" status="green"></x-action-tab>
        </div>
    </section>

	<section class="faq-section mt-5">
        <div class="container">
            @foreach ($faqs as $faq)
                <x-faq.accordion :id="$faq->slug" :question="$faq->question">{!! $faq->answer !!}</x-faq.accordion>
            @endforeach
        </div>
	</section>

	<section class="grey-bg">
        <div class="container">
            <x-contact-form title="Need help? Get in touch"></x-contact-form>
        </div>
	</section>

@endsection

@pushscript('faq')
<script src="{{ mix('/js/templates/faq/index.js') }}" defer></script>
@endpushscript
