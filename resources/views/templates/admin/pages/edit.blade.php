@extends('layouts.admin')

@section('title', $page->title)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('page-info')
@endsection

@section('content')
<a href="{{ url()->previous() }}" class="go-back">@svg('chevron-left') Back to Pages</a>
<h1>{{ $page->title }}</h1>

<div class="page-actions">
    <a class="button outline dark" href="{{ route('page.show', $page->slug) }}" target="_blank" rel="noreferrer">View</a>
    <button id="save" class="button primary ml-1" form="admin-form">Save</button>
</div>

<form id="admin-form" action="{{ route('admin.page.update', $page->slug) }}" method="POST">
    @method('PUT')
    @include('templates.admin.pages.form', compact('page'))
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush