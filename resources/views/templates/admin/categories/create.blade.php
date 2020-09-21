@extends('layouts.admin')

@section('title', 'New category')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/trix.css') }}">
<script src="{{ mix('/js/vendor/trix.js') }}"></script>
@endpush

@section('page-info')
    <button id="save" class="outline dark" form="admin-form">Save</button>
@endsection

@section('content')
<form id="admin-form" action="{{ route('admin.category.store') }}" method="POST">

    @include('templates.admin.categories.form')

</form>
@endsection