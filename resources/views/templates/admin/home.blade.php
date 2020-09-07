@extends('layouts.admin')

@section('title', 'Products')

@section('content')
    <x-admin.table :collection="$products" model-name="product" :fields="['display_name', 'access', 'environments', 'category.title']"></x-admin-table>
@endsection