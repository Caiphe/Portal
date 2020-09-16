@extends('layouts.admin')

@section('title', 'Create FAQ')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/products/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/trix.css') }}">
<script src="{{ mix('/js/vendor/trix.js') }}"></script>
@endpush

@section('page-info')
    <button class="outline dark">Save</button>
@endsection

@section('content')
<form id="edit-form" action="{{ route('admin.faq.store') }}" method="POST">

    @include('templates.admin.faqs.form')

    <hr id="hr">
    <button>Create</button>

</form>
@endsection

@push('scripts')
    <script>
        document.getElementById('save').addEventListener('click', function(){
            document.getElementById('edit-form').submit();
        });
    </script>
@endpush