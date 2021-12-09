@extends('layouts.admin')

@section('title', 'New category')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('content')
<a href="{{ url()->previous() }}" class="go-back">@svg('chevron-left') Back to Categories</a>
<h1>Create Category</h1>

<div class="page-actions">
    <button id="save" class="button primary" form="admin-form">Save</button>
</div>

<form id="admin-form" action="{{ route('admin.category.store') }}" method="POST">
    @include('templates.admin.categories.form')
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush