@extends('layouts.admin')

@section('title', 'Create documentation')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/quill.css') }}">
@endpush

@section('page-info')
    <button id="save" class="outline dark" form="admin-form">Save</button>
@endsection

@section('content')
<form id="admin-form" action="{{ route('admin.doc.store') }}" method="POST">

    @include('templates.admin.docs.form')

</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/quill.js') }}" defer></script>
@endpush