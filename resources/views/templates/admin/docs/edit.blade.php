@extends('layouts.admin')

@section('title', $doc->title)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/trix.css') }}">
<script src="{{ mix('/js/vendor/trix.js') }}"></script>
@endpush

@section('page-info')
    <a class="button outline dark" href="{{ route('doc.show', $doc->slug) }}" target="_blank" rel="noreferrer">View</a>
    <button id="save" class="outline dark ml-1">Save</button>
@endsection

@section('content')
<form id="edit-form" action="{{ route('admin.doc.update', $doc->slug) }}" method="POST">

    @method('PUT')
    
    @include('templates.admin.docs.form', compact('doc'))

</form>
@endsection

@push('scripts')
    <script src="{{ mix('/js/templates/admin/edit.js') }}"></script>
@endpush