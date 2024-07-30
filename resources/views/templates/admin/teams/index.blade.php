@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/teams/index.css') }}">
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'teams')

@section('content')
    <h1>Teams</h1>

    <div class="page-actions">
        <a href="{{ route('admin.team.create') }}" class="button primary page-actions-create" aria-label="Create new team"></a>
    </div>

    <x-admin.filter searchTitle="Team name">
        <div class="filter-item">
            Country
            <select name="country" class="team-country">
                <option value="">Select by country</option>
                @foreach($countries as $code => $name)
                    <option value="{{ $code }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </x-admin.filter>

    <div id="table-data">
        @include('templates.admin.teams.data')
    </div>

    {{ $teams->withQueryString()->links() }}

@endsection

@push('scripts')
<script src="{{ mix('/js/templates/admin/teams/index.js') }}" defer></script>
@endpush
