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
    <buttom type="button" class="button red-button delete-team-btn">Delete team</buttom>

    {{-- Delete team modal --}}
    <x-dialog-box class="team-deletion-confirm" dialogTitle="Delete team">
        <div class="data-container">
            <span>
                Are you sure you want to delete this team ? <br/>
                <strong> {{ $team->name }} </strong>
            </span>
            <p>All team members and applications will be removed from the team.</p>
        </div>

        <div class="bottom-shadow-container button-container">
            <form class="confirm-user-deletion-request-form" method="POST"
                  action="{{ route('admin.team.delete', $team) }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="team_id" value="{{ $team->id }}"/>
                <input type="hidden" name="team_name" value="{{ $team->name }}"/>
                <button type="submit" class="confirm-deletion-btn btn primary">Confirm</button>
                <button type="button" class="cancel" onclick="closeDialogBox(this);">Cancel</button>
            </form>
        </div>
    </x-dialog-box>

    {{-- Transfer ownership Dialog --}}
    <x-dialog-box dialogTitle="Transfer Ownership" class="ownweship-modal-container">

        <p class="remove-user-text dialog-text-padding">Which team member would you like to transfer ownership to? </p>

        <div class="scrollable-users-container">
            <ul class="list-users-container">
                @if ($team->users)
                    @foreach($team->users as $teamUser)
                        @if(!$teamUser->isTeamOwner($team))
                            <li class="each-user">
                                <div class="users-thumbnail"
                                     style="background-image: url({{ $teamUser->profile_picture }})"></div>
                                <div class="user-full-name">{{ $teamUser->full_name }}</div>
                                <div class="check-container">
                                    <x-radio-round-two name="transfer-ownership-check" id="{{ $teamUser->id }}"
                                                       value="{{ $teamUser->email }}"></x-radio-round-two>
                                </div>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>

        <form class="custom-modal-form bottom-shadow-container button-container mt-40">
            <button type="button" id="transfer-btn" data-teamid="{{ $team->id }}" class="inactive">TRANSFER</button>
            <button type="button" class="btn black-bordered mr-10 ownership-removal-btn">CANCEL</button>
        </form>

    </x-dialog-box>
    {{-- Transfer ownership ends --}}

    {{-- Make Admin Modal Container --}}
    <x-dialog-box dialogTitle="Make owner" class="make-admin-modal-container">
        <p class="teammate-text dialog-text-padding">Would you like to make this user a new <strong>owner</strong> of
            this team?</p>
        <p class="admin-user-name bolder-text dialog-text-padding"></p>
        <form class="custom-modal-form bottom-shadow-container button-container mt-40">
            <button type="button" id="make-owner-btn" class="btn primary admin-removal-btn"
                    data-teamid="{{ $team->id }}">SUBMIT
            </button>
            <button type="button" class="btn black-bordered mr-10 make-admin-cancel-btn"
                    onclick="closeDialogBox(this);">CANCEL
            </button>
        </form>
    </x-dialog-box>
    {{-- Make admin ends --}}

    {{-- Make user modal Container --}}
    <x-dialog-box dialogTitle="Make user" class="make-user-modal-container">
        <p class="teammate-text dialog-text-padding">Would you like to make this user a new <strong>owner</strong> of
            this team?</p>
        <p class="make-user-name bolder-text dialog-text-padding"></p>
        <h2 class="team-head" style="display: none; ">Make User</h2>

        <form class="custom-modal-form bottom-shadow-container button-container mt-40" method="post" id="make-user-form"
              action="{{ route('admin.team.user.role', $team) }}">
            @csrf()
            <input type="hidden" name="team_id" id="each-team-id" value=""/>
            <input type="hidden" name="user_id" id="each-user-id" value=""/>
            <input type="hidden" name="user_role" id="each-user-role" value=""/>

            <button type="button" class="btn primary make-user-btn">SUBMIT</button>
            <button type="button" class="btn black-bordered mr-10 user-admin-cancel-btn"
                    onclick="closeDialogBox(this);">CANCEL
            </button>
        </form>
    </x-dialog-box>
    {{-- Make user modal ends --}}

    {{-- Delete User Modal --}}
    <x-dialog-box dialogTitle="Remove user" class="delete-modal-container">

        <p class="teammate-text dialog-text-padding">Are you sure you want to remove this user?</p>
        <p class="user-name user-delete-name bolder-text dialog-text-padding"></p>

        <form id="remove-user-form" class="custom-modal-form bottom-shadow-container button-container mt-40"
              method="post" action="{{ route('admin.team.remove.user', $team) }}">
            @csrf()
            <input type="hidden" value="" name="team_id" class="hidden-team-id"/>
            <input type="hidden" value="" name="team_user_id" class="hidden-team-user-id"/>
            <button type="" class="btn primary remove-user-from-team">REMOVE</button>
            <button type="button" class="btn black-bordered mr-10 cancel-remove-user-btn"
                    onclick="closeDialogBox(this);">CANCEL
            </button>
        </form>

        <div class="confirm-delete-block">
            <button class="confirm-delete-close-modal">@svg('close-popup', '#000')</button>
            <h2 class="team-head custom-head">Warning</h2>
            <p class="remove-user-text">
                <span class="user-name user-to-delete-name"></span> You are the owner/creator of this team profile. To
                be able to delete this account, please assign ownership to another user
            </p>

            <div class="scrollable-users-container">
                <ul class="list-users-container">
                    @if ($team->users)
                        @foreach($team->users as $teamUser)
                            @if(!$teamUser->isTeamOwner($team))
                                <li class="each-user">
                                    <div class="users-thumbnail"
                                         style="background-image: url({{ $teamUser->profile_picture }})"></div>
                                    <div class="user-full-name">{{ $teamUser->full_name }}</div>
                                    <div class="check-container">
                                        <x-radio-round name="transfer-ownership-check" id="{{ $teamUser->id }}"
                                                       value="{{ $teamUser->email }}"></x-radio-round>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>

            <form class="form-delete-user ">
                <button type="button" class="btn primary">REMOVE</button>
                <button type="button" class="btn black-bordered mr-10 cancel-removal-btn">CANCEL</button>
            </form>
        </div>
    </x-dialog-box>
    {{-- Delete User Ends --}}

    {{--Add teammate modal --}}
    <x-dialog-box dialogTitle="Add a team member" class="add-teammate-dialog">
        <form class="form-teammate dialog-custom-form">
            <p class="dialog-text-padding">Invite additional team members or other users</p>
            <div class="form-group-container">
                <input type="text" class="form-team-email teammate-email" placeholder="Add email to invite users" data-url="{{ route('developers.list') }}"/>
            </div>
            <div class="radio-container">
                <x-radio-round id="user-radio" name="role_name" value="team_admin">Administrator</x-radio-round>
                <x-radio-round id="user-radio-two" name="role_name" checked value="team_user">User</x-radio-round>
            </div>

            <div class="form-team-leave bottom-shadow-container button-container">
                <button type="button" class="btn invite-btn primary inactive" data-url="{{ route('teammate.invite', ['id' => $team->id]) }}" data-teamid="{{ $team->id }}">INVITE</button>
                <button type="button" class="btn black-bordered mr-10 close-add-teammate-btn">CANCEL</button>
            </div>
        </form>
    </x-dialog-box>
    {{-- Add teammate ends --}}

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
                    <span class="owner-email">@if($team->owner->email)
                            ({{ $team->owner->email }})
                        @endif</span>
                </div>
            @endif
        </div>

        @if(count($team->users) > 1)
            <a class="button outline dark">Change owner</a>
        @endif

    </div>


    {{-- team members --}}
    <div class="team-custom-head">
        <div class="first">
            <h2 class="member-headings">Team members</h2>
            <span class="gray-text">{{ count($team->users) }} team members</span>
        </div>
        <a class="button outline dark add-team-mate-btn">Add a teammate</a>
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
                        <div class="value-fist-name">{{ $teamUser->first_name }}
                            @if($teamUser->isTeamOwner($team))
                                <span class="owner-tag red-tag">OWNER</span>
                            @endif
                        </div>
                        <div class="value-last-name">{{ $teamUser->last_name }}</div>
                        <div class="value-email">@if($teamUser->email)
                                {{ $teamUser->email }}
                            @endif</div>
                        <div class="value-team-role" id="team-role-{{$teamUser->id}}">@if($teamUser->teamRole($team))
                                {{ $teamUser->teamRole($team)->label }}
                            @endif</div>
                        <div class="value-status">{{ $teamUser->twoFactorStatus() }}</div>
                        <div class="value-actions">

                            @if(!$teamUser->isTeamOwner($team))

                                <div class="block-hide-menu"></div>
                                <button type="button" class="btn-action"></button>
                                <div class="block-actions">

                                    <ul>
                                        {{---  Uses the transfer endpoint--}}
                                        <li>
                                            <button type="button"
                                                    class="make-admin make-owner-btn"
                                                    data-adminname="{{ $teamUser->full_name }}"
                                                    data-invite=""
                                                    data-teamid="{{ $team->id }}"
                                                    @if($teamUser->email)
                                                        data-useremail="{{ $teamUser->email }}">
                                                @endif
                                                Make owner
                                            </button>

                                        </li>
                                        {{---  Uses the invite endpoint--}}
                                        <li>
                                            <button
                                                id="change-role-{{ $teamUser->id }}"
                                                class="make-user"
                                                data-username="{{ $teamUser->full_name }}"
                                                @if($teamUser->email)
                                                    data-useremail="{{ $teamUser->email }}"
                                                @endif
                                                data-teamid="{{ $team->id }}"
                                                data-teamuserid="{{ $teamUser->id }}"
                                                data-userrole="{{ $teamUser->teamRole($team)->name === 'team_user' ? 'team_admin' : 'team_user' }}">
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

                            @endif

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
                    <td><a href="{{ route('admin.dashboard.index', ['aid' => $app]) }}"
                           class="app-link">{{ $app->display_name }}</a></td>
                    <td class="not-on-mobile"><img class="country-flag"
                                                   src="/images/locations/{{ $app->country_code ?? 'globe' }}.svg"
                                                   alt="Country flag"></td>
                    <td class="not-on-mobile">
                        {{--@if($app->developer->email)
                        {{ $app->developer->email }}
                        @endif--}}
                    </td>
                    <td class="not-on-mobile">{{ $app->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="status app-status-{{ $productStatus['status'] }}"
                             aria-label="{{ $productStatus['label'] }}"
                             data-pending="{{ $productStatus['pending'] }}"></div>
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
