@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/users/edit.css') }}">
@endpush

@section('title', 'teams')

@section('content')
    <a href="{{ url()->previous() }}" class="go-back">@svg('chevron-left') Back to teams</a>
    <h1>Create a Team</h1>

    <div class="full">
        <div class="editor-field">
            <h2>Details</h2>
            @include('templates.admin.team.forms.create_team')
        </div>
    </div>

@endsection
@push('scripts')

@endpush
