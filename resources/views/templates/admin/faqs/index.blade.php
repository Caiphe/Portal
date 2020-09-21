@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'FAQs')

@section('page-info')
    <a href="{{ route('admin.faq.create') }}" class="button dark outline">Create</a>
@endsection

@section('content')
    <x-admin.table :collection="$faqs" model-name="faq" :fields="['question', 'category.title']"></x-admin.table>
@endsection