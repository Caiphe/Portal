@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/faq/index.css') }}">
@endpush

@section('title','FAQs')

@section('content')
	<x-heading heading="FAQs"></x-heading>
	<section class="faq-section mt-5">
	@foreach ($faqs as $faq)
		<x-faq.accordion :question="$faq->question">{!! $faq->answer !!}</x-faq.accordion>
	@endforeach
	</section>
	<section class="contact-section">
		<x-contact-form title="Need help? Get in touch"/> 
	</section>	
@endsection

@pushscript('faq')
<script src="{{ mix('/js/templates/faq/index.js') }}" defer></script>
@endpushscript
