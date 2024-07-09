@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/teams/create.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/teams/forms.css') }}">
@endpush

@section('title', 'teams')

@section('content')
    <a href="{{ url()->previous() }}" class="go-back">@svg('chevron-left') Back to teams</a>
    <h1>Create a Team</h1>

    <div class="full">

            @include('templates.admin.team.forms.create_team')

    </div>

@endsection
@push('scripts')
    <script src="{{ mix('/js/templates/teams/create.js') }}"></script>
    <script src="{{ mix('/js/templates/teams/create-update-validation.js') }}"></script>
@endpush
