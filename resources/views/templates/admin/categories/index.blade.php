@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Categories')

@section('page-info')
    <a href="{{ route('admin.category.create') }}" class="button dark outline">Create</a>
@endsection

@section('content')
    <x-admin.table :collection="$categories" :fields="['title', 'theme']"></x-admin.table>
@endsection