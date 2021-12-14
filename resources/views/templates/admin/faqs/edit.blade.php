@extends('layouts.admin')

@section('title', $faq->question)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('content')
<a href="{{ route('admin.faq.index') }}" class="go-back">@svg('chevron-left') Back to FAQs</a>
<h1>{{ $faq->question }}</h1>

<div class="page-actions">
    <a class="button primary" href="{{ route('faq.show', $faq->slug) }}" target="_blank" rel="noreferrer">View question</a>
</div>

<form id="admin-form" action="{{ route('admin.faq.update', $faq->slug) }}" method="POST">

    @method('PUT')
    
    @include('templates.admin.faqs.form', compact('faq'))
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush