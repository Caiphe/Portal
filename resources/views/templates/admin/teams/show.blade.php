@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.admin')
@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
<link rel="stylesheet" href="{{ mix('/css/templates/admin/teams/show.css') }}">
@endpush

@section('title', 'teams')

@section('content')

    <a href="{{ route('admin.team.index') }}" class="go-back">@svg('chevron-left') Back to teams</a>
    <h1 class="main-team">{{ $team->name }}</h1>
    <buttom type="button" class="button red-button delete-team-btn" >Delete team</buttom>


    <x-dialog-box class="team-deletion-confirm" dialogTitle="Delete team">
        <div class="data-container">
            <span>
                Are you sure you want to delete this team ? <br/>  
                <strong> {{ $team->name }} </strong>
            </span>
            <p>All team members and applications will be removed from the team.</p>
        </div>
    
        <div class="bottom-shadow-container button-container">
            <form class="confirm-user-deletion-request-form" method="POST" action="{{ route('admin.team.delete', $team) }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="team_id" value="{{ $team->id }}" />
                <input type="hidden" name="team_name" value="{{ $team->name }}" />
                <button type="submit" class="confirm-deletion-btn btn primary">Confirm</button>
                <button type="button" class="cancel" onclick="closeDialogBox(this);">Cancel</button>
            </form>
        </div>
    </x-dialog-box>


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
                    @if($team->email)
                    <div class="each-details">
                        <div class="detail-key">Team owner</div>
                        <div class="detail-value">{{ Str::limit($team->email, 20, '...')}}</div>
                    </div>
                    @endif

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

        <div class="owner-block">
            @if($team->owner)
            <div class="member-thumbnail" style="background-image: url({{ $team->owner->profile_picture }})"></div>
            
            <div class="owner-block-profile">
                <span class="owner-full-name">{{ $team->owner->full_name }}</span>
                <span class="owner-email"> ({{ $team->owner->email }})</span>
            </div>
            @endif
        </div>

        <a class="button outline dark">Change owner</a>

    </div>


    {{-- team members --}}
    <div class="team-custom-head">
        <div class="first">
            <h2 class="member-headings">Team members</h2>
            <span class="gray-text">{{ count($team->users) }} team members</span>
        </div>
        <a class="button outline dark add-teammate-btn" href="#">
            <span class="teammate">Add a teammate</span>
            <span class="team-cross">@svg('plus')</span>
        </a>
    </div>

    @if(count($team->users) > 0)
    <div class="main-member-container">
        <div class="memeber-list">
            <div class="header">
                <div class="column-fist-name">First name</div>
                <div class="column-last-name">Last name</div>
                <div class="column-email">Email address</div>
                <div class="column-team-role">Team Role</div>
                <div class="column-status">2FA Status</div>
                <div class="column-actions"></div>
            </div>

            @foreach ($team->users as $teamUser)
            <div class="each-member-body">
                <div class="value-fist-name">{{ $teamUser->first_name }}</div>
                <div class="value-last-name">{{ $teamUser->last_name }}</div>
                <div class="value-email">{{ $teamUser->email }}</div>
                <div class="value-team-role">{{ $teamUser->teamRole($team)->label }}</div>
                <div class="value-status">{{ $teamUser->twoFactorStatus() }}</div>
                <div class="value-actions">
                    <button class="btn-action"></button>

                    <div class="block-actions">
                        @php
                            
                        @endphp
                        <ul>
                            {{---  Uses the transfer endpoint--}}
                            {{-- @if($isOwner) --}}
                            <li>
                                <button
                                    class="make-admin make-owner-btn"
                                    data-adminname="{{ $teamUser->full_name }}"
                                    data-invite=""
                                    data-teamid="{{ $team->id }}"
                                    data-useremail="{{ $teamUser->email }}">
                                    Make owner
                                </button>
                            </li>
                            {{-- @endif --}}

                            {{---  Uses the invite endpoint--}}
                            <li>
                                <button
                                    id="change-role-{{ $teamUser->id }}"
                                    class="make-user"
                                    data-username="{{ $teamUser->full_name }}"
                                    data-useremail="{{ $teamUser->email }}"
                                    data-teamid="{{ $team->id }}"
                                    data-teamuserid="{{ $teamUser->id }}"
                                    data-userrole = "{{ $teamUser->teamRole($team)->name === 'team_user' ? 'team_admin' : 'team_user' }}">
                                    {{ $teamUser->teamRole($team)->name === 'team_user' ? 'Make administrator' : 'Make user' }}
                                </button>
                            </li>

                            {{---  Uses the leave endpoinst --}}
                            <li>
                                <button
                                    class="user-delete"
                                    data-usernamedelete="{{ $teamUser->full_name }}"
                                    data-teamid="{{ $team->id }}"
                                    data-teamuserid="{{ $teamUser->id }}">
                                    Delete
                                </button>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
        <div class="no-content-found"> Team currently has no members</div>
    @endif

    {{-- team members --}}
    <div class="team-custom-head">
        <div class="first">
            <h2 class="member-headings">Team Apps</h2>
            <span class="gray-text">{{ count($team->apps) }} applications</span>
        </div>
    </div>

    {{-- apps list --}}
    @if($teamsApps)
        <table id="apps-list" class="table-list">
            <thead>
                <tr>
                    <th><a href="?sort=name&order={{ $order }}">Name @svg('chevron-sorter')</a></th>
                    <th><a href="?sort=country_code&order={{ $order }}">Country @svg('chevron-sorter')</a></th>
                    <th><a href="?sort=products_count&order={{ $order }}">Creator @svg('chevron-sorter')</a></th>
                    <th><a href="?sort=created_at&order={{ $order }}">Create at @svg('chevron-sorter')</a></th>
                    <th><a href="?sort=status&order={{ $order }}">Status @svg('chevron-sorter')</a></th>
                </tr>
            </thead>

                @foreach($teamsApps as $app)
                @php
                    $productStatus = $app->product_status;
                @endphp

                <tr class="user-app" data-country="{{ $app->country_code }}">
                    <td><a href="{{ route('admin.dashboard.index', ['aid' => $app]) }}" class="app-link">{{ $app->display_name }}</a></td>
                    <td class="not-on-mobile"><img class="country-flag" src="/images/locations/{{ $app->country_code ?? 'globe' }}.svg" alt="Country flag"></td>
                    <td class="not-on-mobile">{{ $app->developer->email }}</td>
                    <td class="not-on-mobile">{{ $app->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="status app-status-{{ $productStatus['status'] }}" aria-label="{{ $productStatus['label'] }}" data-pending="{{ $productStatus['pending'] }}"></div>
                        <a class="go-to-app" href="{{ route('admin.dashboard.index', ['aid' => $app]) }}">@svg('chevron-right')</a>
                    </td>
                </tr>
                @endforeach
        
        </table>
    @else
    <div class="no-content-found"> Team currently has no apps</div>
    @endif

@endsection

@push('scripts')
<script src="{{ mix('/js/templates/admin/teams/show.js') }}" defer></script>

@endpush