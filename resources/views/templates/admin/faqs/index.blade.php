@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'FAQs')

@section('content')
    <h1>FAQs</h1>

    <div class="page-actions">
        <a href="{{ route('admin.faq.create') }}" class="button primary">Create FAQ</a>
    </div>

    <x-admin.filter searchTitle="Questions"></x-admin.filter>

    <div id="table-data">
        @include('components.admin.list', [
            'collection' => $faqs,
            'fields' => ['Question' => 'question', 'Category' => 'category.title'],
            'modelName' => 'faq'
        ])
    </div>
@endsection

@push('scripts')
<script src="{{ mix('/js/templates/admin/index.js') }}" defer></script>
@endpush