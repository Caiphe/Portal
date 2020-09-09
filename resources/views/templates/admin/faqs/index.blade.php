@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'FAQs')

@section('content')
    <a href="{{ route('admin.faq.create') }}" class="button mb-2">Create FAQ</a>
    <x-admin.table :collection="$faqs" model-name="faq" :fields="['question', 'category.title']"></x-admin.table>
@endsection