@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Categories')

@section('content')
    <a href="{{ route('admin.category.create') }}" class="button mb-2">Create category</a>
    <x-admin.table :collection="$categories" :fields="['title', 'theme']"></x-admin.table>
@endsection