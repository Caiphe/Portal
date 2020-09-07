@extends('layouts.admin')

@section('title', 'Bundles')

@section('content')
    <x-admin.table :collection="$bundles" model-name="bundle" :fields="['display_name', 'category.title']"></x-admin-table>
@endsection