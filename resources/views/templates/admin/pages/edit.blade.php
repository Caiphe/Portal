@extends('layouts.admin')

@section('title', $page->title)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/trix.css') }}">
<script src="{{ mix('/js/vendor/trix.js') }}"></script>
@endpush

@section('page-info')
    <a class="button outline dark" href="{{ route('page.show', $page->slug) }}" target="_blank" rel="noreferrer">View</a>
    <button id="save" class="outline dark ml-1">Save</button>
@endsection

@section('content')
<form id="edit-form" action="{{ route('admin.page.update', $page->slug) }}" method="POST">

    @method('PUT')
    
    @include('templates.admin.pages.form', compact('page'))

</form>
@endsection

@push('scripts')
    <script src="{{ mix('/js/templates/admin/edit.js') }}"></script>
@endpush