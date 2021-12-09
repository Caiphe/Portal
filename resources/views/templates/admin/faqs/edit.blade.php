@extends('layouts.admin')

@section('title', $faq->question)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('content')
<a href="{{ url()->previous() }}" class="go-back">@svg('chevron-left') Back to FAQs</a>
<h1>{{ $faq->question }}</h1>

<div class="page-actions">
    <a class="button outline dark" href="{{ route('faq.show', $faq->slug) }}" target="_blank" rel="noreferrer">View Question</a>
    <button id="save" class="button primary ml-1" form="admin-form">Save</button>
</div>

<form id="admin-form" action="{{ route('admin.faq.update', $faq->slug) }}" method="POST">

    @method('PUT')
    
    @include('templates.admin.faqs.form', compact('faq'))
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush