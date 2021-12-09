@extends('layouts.admin')

@section('title', 'Create FAQ')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('content')
<a href="{{ url()->previous() }}" class="go-back">@svg('chevron-left') Back to FAQs</a>
<h1>Create FAQ</h1>

<div class="page-actions">
    <button class="button primary" form="admin-form">Save</button>
</div>

<form id="admin-form" action="{{ route('admin.faq.store') }}" method="POST">
    @include('templates.admin.faqs.form')
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush