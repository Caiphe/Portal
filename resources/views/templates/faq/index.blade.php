@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="/css/templates/faq/index.css">
@endpush

@section('content')
	<x-heading heading="FAQs"></x-heading>
	@foreach ($faqs as $faq)
		<x-faq.accordion :question="$faq->question">{!! $faq->answer !!}</x-faq.accordion>
	@endforeach
	<section class="grey-section">
		<x-contact-form title="Need help? Get in touch"/> 
	</section>	
@endsection

@pushscript('faq')
<script src="/js/templates/faq/index.js" defer></script>
@endpushscript
