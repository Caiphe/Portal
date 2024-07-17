
@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/teams/create.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/teams/forms.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
@endpush

@section('title', 'teams')

@section('content')
    <a href="{{ url()->previous() }}" class="go-back">@svg('chevron-left') Back to teams</a>
    <h1>Team Profile</h1>

    <div class="full">
        @include('templates.admin.teams.forms.edit_team')
    </div>
@endsection
@push('scripts')
    <script src="{{ mix('/js/templates/admin/teams/edit_form.js') }}"></script>
@endpush
