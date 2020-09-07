@extends('layouts.admin')

@section('title', $faq->question)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/products/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/vendor/trix.css') }}">
<script src="{{ mix('/js/vendor/trix.js') }}"></script>
@endpush

@section('content')
<a class="button mb-2" href="{{ route('faq.show', $faq->slug) }}" target="_blank" rel="noreferrer">View faq</a>

<form id="edit-form" action="{{ route('admin.faq.update', $faq->slug) }}" method="POST">

    @method('PUT')
    
    @include('templates.admin.faqs.form', compact('faq'))

    <hr id="hr">
    <button>Update</button>

</form>
@endsection