@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Products')

@section('content')
    <x-admin.table :collection="$products" model-name="product" :fields="['display_name', 'access', 'environments', 'category.title']"></x-admin-table>
@endsection