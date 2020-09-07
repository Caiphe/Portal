@extends('layouts.admin')

@section('title', 'Documentation')

@section('content')
    <x-admin.table :collection="$docs" model-name="doc" :fields="['title']"></x-admin.table>
@endsection