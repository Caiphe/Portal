@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
<link rel="stylesheet" href="{{ mix('/css/templates/admin/teams/index.css') }}">
@endpush

@section('title', 'teams')

@section('content')
    <h1>Teams</h1>

    <div class="page-actions">
        <a href="{{ route('admin.team.create') }}" class="button primary page-actions-create" aria-label="Create new team"></a>
    </div>

    <x-admin.filter searchTitle="User's name / email address">
        <label class="filter-item" for="status">
            User status
            <select name="status" class="users-status">
                <option value="">Select by status</option>
                <option value="verified" @if(request()->get('status') === 'verified') selected @endif>Verified</option>
                <option value="not_verified" @if(request()->get('status') === 'not_verified') selected @endif>Not verified</option>
            </select>
        </label>
    </x-admin.filter>

    <div id="teams-table-data">
      <div class="header">
        <p class="column-team-name">Team name</p>
        <p class="column-team-country">Country</p>
        <p class="column-team-owner">Team Owner</p>
        <p class="column-team-members">Members</p>
        <p class="column-team-apps">apss</p>
        <p class="column-created_at">Created at</p>
        <p class="column-actions">Actions</p>
      </div>

      <div class="body-container">
        @foreach ($teams as $team)
        <div class="each-team">
            <div class="value-team-name">{{ $team->name }}</div>
            <div class="value-team-country">
               <img src="/images/locations/{{ $team->country }}.svg" src="team-{{ $team->country }}" />
            </div>
            <div class="value-team-owner">{{ $team->owner->email }}</div>
            <div class="value-team-members">{{ $team->country }}</div>
            <div class="value-team-apps">{{ $team->country }}</div>
            <div class="value-team-created_at">{{ date('d M Y', strtotime($team->created_at)) }}</div>
            <div class="value-team-actions">
                <a href="{{ route('admin.team.show', ['team' => $team]) }}" class="button"> @svg('pencil') Edit</a>
                <button class="sl-button">@svg('trash') Delete</button>
            </div>
        </div>
        @endforeach

      </div>

    </div>

@endsection
@push('scripts')
<script src="{{ mix('/js/templates/admin/index.js') }}" defer></script>
<script>
    ajaxifyOnPopState = updateFilters;
    function updateFilters(params) {
        document.getElementById('search-page').value = params['q'] || '';
        document.querySelector('.users-status').value = params['verified'] || '';
    }
</script>
@endpush
