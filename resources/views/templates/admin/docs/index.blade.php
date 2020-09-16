@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Documentation')

@section('page-info')
    <a href="{{ route('admin.doc.create') }}" class="button dark outline">Create</a>
@endsection

@section('content')
    <x-admin.table :collection="$docs" model-name="doc" :fields="['title']"></x-admin.table>
@endsection