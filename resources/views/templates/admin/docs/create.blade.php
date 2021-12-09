@extends('layouts.admin')

@section('title', 'Create documentation')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('content')
<a href="{{ url()->previous() }}" class="go-back">@svg('chevron-left') Back to Documentation</a>
<h1>Create Documentation</h1>

<div class="page-actions">
    <button id="save" class="button primary" form="admin-form">Save</button>
</div>

<form id="admin-form" action="{{ route('admin.doc.store') }}" method="POST">
    @include('templates.admin.docs.form')
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush