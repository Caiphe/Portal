@extends('layouts.admin')

@section('title', 'Documentation')

@section('content')
    <a href="{{ route('admin.doc.create') }}" class="button mb-2">Create documentation</a>
    <x-admin.table :collection="$docs" model-name="doc" :fields="['title']"></x-admin.table>
@endsection