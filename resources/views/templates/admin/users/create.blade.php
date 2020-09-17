@extends('layouts.admin')

@section('title', 'New user')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('page-info')
    <button id="save" class="outline dark">Save</button>
@endsection

@section('content')
<form id="edit-form" action="{{ route('admin.user.store') }}" method="POST">

    @include('templates.admin.users.form')

</form>
@endsection

@push('scripts')
    <script src="{{ mix('/js/templates/admin/edit.js') }}"></script>
@endpush