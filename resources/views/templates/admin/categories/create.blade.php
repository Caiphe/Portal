@extends('layouts.admin')

@section('title', 'New category')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/products/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/trix.css') }}">
<script src="{{ mix('/js/vendor/trix.js') }}"></script>
@endpush

@section('page-info')
    <button id="save" class="outline dark">Save</button>
@endsection

@section('content')
<form id="edit-form" action="{{ route('admin.category.store') }}" method="POST">

    @include('templates.admin.categories.form')

</form>
@endsection

@push('scripts')
    <script src="{{ mix('/js/templates/admin/edit.js') }}"></script>
@endpush