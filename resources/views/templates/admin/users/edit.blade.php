@extends('layouts.admin')

@section('title', $user->full_name)

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('page-info')
    <button id="save" class="outline dark ml-1">Save</button>
@endsection

@section('content')
<form id="edit-form" action="{{ route('admin.user.update', $user->slug) }}" method="POST">

    @method('PUT')
    @include('templates.admin.users.form')

</form>
@endsection

@push('scripts')
    <script src="{{ mix('/js/templates/admin/edit.js') }}"></script>
@endpush