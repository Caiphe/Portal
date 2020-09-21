@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Users')

@section('page-info')
    <a href="{{ route('admin.user.create') }}" class="button dark outline">Create</a>
@endsection

@section('content')
    <x-admin.table :collection="$users" :fields="['first_name', 'last_name', 'email', 'roles_list']"></x-admin-table>
@endsection