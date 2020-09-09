@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Pages')

@section('content')
    <a href="{{ route('admin.page.create') }}" class="button mb-2">Create page</a>
    <x-admin.table :collection="$pages" model-name="page" :fields="['title']"></x-admin.table>
@endsection