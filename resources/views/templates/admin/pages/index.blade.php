@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
    <x-admin.table :collection="$pages" model-name="page" :fields="['title']"></x-admin.table>
@endsection