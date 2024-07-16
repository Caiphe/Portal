@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/templates/admin/teams/show.css') }}">
@endpush

@section('title', 'teams')

@section('content')
    <h1>{{ $team->name }}</h1>

    <div class="page-actions">
        <a href="{{ route('admin.team.create') }}" class="button red-button page-actions-create" aria-label="Delete team"></a>
    </div>

    <div class="form-container editor-field">
        <h2>Details</h2>

        <div class="teams-details-block">
            <div class="first-block">
                <div class="image-container" style="background-image: url({{ $team['logo'] }})"></div>
                <div class="team-name-container">
                    <h2 class="team-name">{{ $team->name }}</h2>
                    <div class="team-description">{{ $team->description }}</div>
                </div>
            </div>

            <div class="team-more-details">
                <div class="left-block each-block-data">
                    <div class="each-details">
                        <div class="detail-key">Team URL</div>
                        <div class="detail-value">{{ $team->url }}</div>
                    </div>
                    <div class="each-details">
                        <div class="detail-key">Contact number</div>
                        <div class="detail-value">{{ $team->contact }}</div>
                    </div>
                    <div class="each-details">
                        <div class="detail-key">Country</div>
                        <div class="detail-value">{{ $country }}</div>
                    </div>
                </div>

                <div class="right-block each-block-data">
                    <div class="each-details">
                        <div class="detail-key">Team URL</div>
                        <div class="detail-value">{{ $team->url }}</div>
                    </div>
                    <div class="each-details">
                        <div class="detail-key">Team members</div>
                        <div class="detail-value">{{ count($team->users) }}</div>
                    </div>
                    <div class="each-details">
                        <div class="detail-key">Team apps</div>
                        <div class="detail-value">{{ count($team->apps) }}</div>
                    </div>
                </div>
            </div>

            <a class="button outline dark team-edit"> Edit team profile </a>
        </div>
    </div>

    {{-- Owner block --}}
    <div class="team-owner">
        <h2>Team owner</h2>
        <a class="button outline dark">Change owner</a>
    </div>

    <div class="owner-block">
        @foreach($team->users as $teamUser)
        @if($teamUser->isTeamOwner($team))
        <div class="member-thumbnail" style="background-image: url({{ $teamUser->profile_picture }})"></div>
        <span class="owner-full-name">{{ $teamUser->full_name }}</span>
        <span class="owner-email">({{ $teamUser->email }})</span>
        @endif
        @endforeach
    </div>

    {{-- team members --}}
    <div class="team-custom-head">
        <div class="first">
            <h2 class="member-headings">Team members</h2>
            <span class="gray-text">{{ count($team->users) }} team members</span>
        </div>
        <a class="button outline dark">Add a teammate</a>
    </div>

    <div class="memeber-list">
        <div class="header">
            <div class="column-fist-name">First name</div>
            <div class="column-last-name">Last name</div>
            <div class="column-email">Email address</div>
            <div class="column-team-role">Team Role</div>
            <div class="column-status">2FA Status</div>
            <div class="column-actions"></div>
          </div>

        @foreach ($team->users as $user)
        <div class="each-member-body">
            <div class="value-fist-name">{{ $user->first_name }}</div>
            <div class="value-last-name">{{ $user->last_name }}</div>
            <div class="value-email">{{ $user->email }}</div>
            <div class="value-team-role">{{ $user->teamRole($team)->label }}</div>
            <div class="value-status">{{ $user->twoFactorStatus() }}</div>
            <div class="value-actions">
                <button class="btn-action"></button>
            </div>
        </div>
        @endforeach
    </div>


    {{-- team members --}}
    <div class="team-custom-head">
        <div class="first">
            <h2 class="member-headings">Team Apps</h2>
            <span class="gray-text">{{ count($team->apps) }} applications</span>
        </div>
    </div>

    {{-- apps list --}}
<table id="apps-list" class="table-list">
    <thead>
        <tr>
            <th><a href="?sort=name&order={{ $order }}">Name @svg('chevron-sorter')</a></th>
            <th><a href="?sort=products_count&order={{ $order }}">Products @svg('chevron-sorter')</a></th>
            <th><a href="?sort=created_at&order={{ $order }}">Registered @svg('chevron-sorter')</a></th>
            <th><a href="?sort=country_code&order={{ $order }}">Country @svg('chevron-sorter')</a></th>
            <th><a href="?sort=status&order={{ $order }}">Status @svg('chevron-sorter')</a></th>
        </tr>
    </thead>

    @if($teamsApps)
        @foreach($teamsApps as $app)
        @php
            $productStatus = $app->product_status;
        @endphp

        <tr class="user-app" data-country="{{ $app->country_code }}">
            <td><a href="{{ route('admin.dashboard.index', ['aid' => $app]) }}" class="app-link">{{ $app->display_name }}</a></td>
            <td class="not-on-mobile">{{ $app->products_count }}</td>
            <td class="not-on-mobile">{{ $app->created_at->format('d M Y') }}</td>
            <td class="not-on-mobile"><img class="country-flag" src="/images/locations/{{ $app->country_code ?? 'globe' }}.svg" alt="Country flag"></td>
            <td>
                <div class="status app-status-{{ $productStatus['status'] }}" aria-label="{{ $productStatus['label'] }}" data-pending="{{ $productStatus['pending'] }}"></div>
                <a class="go-to-app" href="{{ route('admin.dashboard.index', ['aid' => $app]) }}">@svg('chevron-right')</a>
            </td>
        </tr>
        @endforeach
    @else
    <tr>
        <td>Developer currently has no apps</td>
    </tr>
    @endif
</table>



@endsection

@push('scripts')
@endpush