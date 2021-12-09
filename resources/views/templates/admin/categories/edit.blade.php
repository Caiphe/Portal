@extends('layouts.admin')

@section('title', $category->title)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('content')
<a href="{{ url()->previous() }}" class="go-back">@svg('chevron-left') Back to Categories</a>
<h1>{{ $category->title }}</h1>

<div class="page-actions">
    <a class="button outline dark" href="{{ route('category.show', $category->slug) }}" target="_blank" rel="noreferrer">View</a>
    <button id="save" class="button primary ml-1" form="admin-form">Save</button>
</div>

<form id="admin-form" action="{{ route('admin.category.update', $category->slug) }}" method="POST">
    @method('PUT')
    @include('templates.admin.categories.form', compact('category', 'content'))
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush