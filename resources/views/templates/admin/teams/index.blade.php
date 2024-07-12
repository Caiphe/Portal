@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
<link rel="stylesheet" href="{{ mix('/css/templates/admin/teams/index.css') }}">
@endpush

@section('title', 'teams')

@section('content')
    <h1>Teams</h1>

    <div class="page-actions">
        <a href="{{ route('admin.user.create') }}" class="button primary page-actions-create" aria-label="Create new team"></a>
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
      <div class="header">
        <div class="column-team-name">Team name</div>
        <div class="column-team-country">Country</div>
        <div class="column-team-owner">Team Owner</div>
        <div class="column-team-members">Members</div>
        <div class="column-team-apps">Apps</div>
        <div class="column-created_at">Created at</div>
        <div class="column-actions">Actions</div>
      </div>

      <div class="body body-container">
        @foreach ($teams as $team)
        <div class="each-team">
            <div class="value-team-name">{{ $team->name }}</div>
            <div class="value-team-country">
               <img src="/images/locations/{{ $team->country }}.svg" src="team-{{ $team->country }}" />
            </div>
            <div class="value-team-owner">{{ $team->owner->email }}</div>
            <div class="value-team-members">{{ count($team->users) }}</div>
            <div class="value-team-apps">{{ count($team->apps) }}</div>
            <div class="value-team-created_at">{{ date('d M Y', strtotime($team->created_at)) }}</div>
            
            <div class="value-team-actions">
                <a href="" class="actions-btn"> @svg('pencil', "#0c678f") Edit</a>
                <a class="actions-btn">@svg('trash', "#0c678f") Delete</a>
            </div>

            <button class="sl-button reset team-mobile-action">
                @svg('more-vert', '#0c678f')
                @svg('chevron-right', '#0c678f')
            </button>

        </div>
        @endforeach
       
      </div>
    </div>

    {{ $teams->withQueryString()->links() }}


@endsection

@push('scripts')
<script src="{{ mix('/js/templates/admin/index.js') }}" defer></script>
<script src="{{ mix('/js/templates/admin/teams/index.js') }}" defer></script>
@endpush
