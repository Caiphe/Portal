@extends('layouts.admin')

@section('title', 'Create page')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('page-info')
    <button id="save" class="outline dark" form="admin-form">Save</button>
@endsection

@section('content')
<form id="admin-form" action="{{ route('admin.page.store') }}" method="POST">

    @include('templates.admin.pages.form')

</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/ckeditor.js') }}" defer></script>
@endpush