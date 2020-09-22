@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Pages')

@section('page-info')
    <a href="{{ route('admin.page.create') }}" class="button dark outline">Create</a>
@endsection

@section('content')
    <x-admin.table :collection="$pages" model-name="page" :fields="['title']"></x-admin.table>
@endsection