@extends('layouts.admin')

@section('title', 'Create FAQ')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/quill.css') }}">
@endpush

@section('page-info')
    <button class="outline dark" form="admin-form">Save</button>
@endsection

@section('content')
<form id="admin-form" action="{{ route('admin.faq.store') }}" method="POST">

    @include('templates.admin.faqs.form')
    
</form>
@endsection

@push('scripts')
<script src="{{ mix('/js/components/quill.js') }}" defer></script>
@endpush