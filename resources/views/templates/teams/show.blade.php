@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/teams/show.css') }}">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
<x-sidebar-accordion id="sidebar-accordion" active="/teams/create" :list="
    [ 'Manage' =>
        [
            [ 'label' => 'Profile', 'link' => '/profile'],
            [ 'label' => 'My apps', 'link' => '/apps'],
            [ 'label' => 'Teams', 'link' => '/teams/create']
        ],
        'Discover' =>
        [
            [ 'label' => 'Browse all products', 'link' => '/products'],
            [ 'label' => 'Working with our products','link' => '/getting-started'],
        ]
    ]
    " />
@endsection

@section('title')
Teams
@endsection

@section('content')
<x-heading heading="Teams" tags="Dashboard">
    <a href="/" class="button dark outline">Edit profile</a>
</x-heading>

{{-- Edit teammate --}}
<div class="modal-container">
    <div class="overlay-container"></div>
    <div class="add-teammate-block">

        <button class="close-modal">@svg('close', '#000')</button>

        <h2 class="team-head">Add teammate</h2>
        <p class="teammate-text">Invite additional team members or other users</p>
        <form class="form-teammate">
            <div class="form-group-container">
                <input type="text" class="form-control teammate-email" placeholder="Add email to invite users" />
                <button type="submit" class="invite-btn">INVITE</button>
            </div>
            <div class="radio-container">
                <x-radio-round id="user-radio" name="role_name" value="Administrator">Administrator</x-radio-round>
                <x-radio-round id="user-radio" name="role_name" value="user">User</x-radio-round>
            </div>
        </form>
    </div>
</div>
{{-- Edit team mate ends --}}

<div class="header-block team-name-logo">
    {{-- To replace with the company logo --}}
   <div class="team-logo" style="background-image: url('/images/user-thumbnail.jpg')"></div> <h2>
        {{  $team->name }}
    </h2>
</div>

<div class="mt-2">
    <h5>Team bio</h5>
    <p>{{ $team->description }}</p>
    <div class="detail-left team-detail-left">
        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Contact number</strong></div>
            <div class="detail-item detail-item-description">{{ $team->contact }}</div>
        </div>

        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Company URL</strong></div>
            <div class="detail-item detail-item-description">{{ $team->url }}</div>
        </div>

        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Country</strong></div>
            <div class="detail-item detail-item-description">{{ $team->country }} </div>
        </div>
    </div>
    <div class="column">
        <div class="team-members-header">
            <h2>Team membership</h2>
            <button class="outline dark add-team-mate-btn">Add a teammate</button>
        </div>
    </div>
    <div class="column team-members-list">
        <table class="team-members">
            <tr class="table-title" >
                <td class="bold">Member name @svg('arrow-down' ,'#cdcdcd')</td>
                <td class="bold">Role @svg('arrow-down' ,'#cdcdcd')</td>
                <td class="bold">2FA Status @svg('arrow-down' ,'#cdcdcd')</td>
            </tr>

            @foreach($team->users as $teamUser)
                <tr>
                    <td class="member-name-profile">
                        <div class="member-thumbnail"  style="background-image: url('/images/user-thumbnail.jpg')"></div>
                        <p>
                            <strong> {{ $teamUser->first_name }} {{ $teamUser->last_name }}</strong>
                            ({{ $teamUser->email }})
                        </p>

                        @if($teamUser->isTeamOwner())
                            <span class="owner-tag red-tag">OWNER</span>
                        @endif
                    </td>
                    <td>{{ $teamUser->roles()->first()->name  === 'admin' ? 'Administrator' : ucfirst($teamUser->roles()->first()->name) }}</td>
                    <td>{{ $teamUser->twoFactorStatus() }}</td>
                </tr>
            @endforeach

        </table>

        <button class="outline dark add-team-mate-btn-mobile">Add a teammate</button>

    </div>
    <div class="column" id="app-index">
        <div class="row">
            <div class="approved-apps">
                <div class="heading-app">
                    @svg('chevron-down', '#000000')

                    <h3>Approved Apps</h3>
                </div>

                <div class="my-apps my-app-details">
                    <div class="head headings-container">
                        <div class="column-heading">
                            <h4 class="app-heading">App Name @svg('arrow-down' ,'#cdcdcd')</h4>
                        </div>

                        <div class="column-heading">
                            <h4 class="app-heading">Callback URL @svg('arrow-down' ,'#cdcdcd')</h4>
                        </div>

                        <div class="column-heading">
                            <h4 class="app-heading">Country @svg('arrow-down' ,'#cdcdcd')</h4>
                        </div>

                        <div class="column-heading">
                            <h4 class="app-heading">Creator @svg('arrow-down' ,'#cdcdcd')</h4>
                        </div>

                        <div class="column-heading">
                            <h4 class="app-heading">Date created @svg('arrow-down' ,'#cdcdcd')</h4>
                        </div>
                        <div class="column-heading">
                            <h4 class="app-heading"></h4>
                        </div>

                    </div>

                    <div class="body">
                        @forelse($approvedApps as $app)
                            @if(!empty($app['attributes']))
                                <x-team-app
                                    :app="$app"
                                    :attr="$app['attributes']"
                                    :details="$app['developer']"
                                    :countries="!is_null($app->country) ? [$app->country->code => $app->country->name] : ['globe' => 'globe']"
                                    :type="$type = 'approved'">
                                </x-team-app>
                            @endif
                        @empty
                            <p>No approved apps.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="app">
            <div class="revoked-apps">
                <div class="heading-app">
                    @svg('chevron-down', '#000000')

                    <h3>Revoked Apps</h3>
                </div>

                <div class="my-apps">
                    <div class="head">
                        <div class="column">
                            <p>App name</p>
                        </div>

                        <div class="column">
                            <p>Callback URL</p>
                        </div>

                        <div class="column">
                            <p>Country</p>
                        </div>

                        <div class="column">
                            <p>Creator</p>
                        </div>

                        <div class="column">
                            <p>Date created</p>
                        </div>

                        <div class="column">

                        </div>
                    </div>
                    <div class="body">
                        @forelse($revokedApps as $app)
                            @if(!empty($app['attributes']))
                                <x-app
                                    :app="$app"
                                    :attr="$app['attributes']"
                                    :details="$app['developer']"
                                    :countries="!is_null($app->country) ? [$app->country->code => $app->country->name] : ['globe' => 'globe']"
                                    :type="$type = 'revoked'">
                                </x-app>
                            @endif
                        @empty
                            <p>No revoked apps.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var clodeModal = document.querySelector('.close-modal');
    var modalContainer = document.querySelector('.modal-container');
    var addTeammateBtn = document.querySelector('.add-team-mate-btn');
    var addTeamMobile = document.querySelector('.add-team-mate-btn-mobile');

    addTeammateBtn.addEventListener('click', function(){
        modalContainer.classList.add('show');
    });

    addTeamMobile.addEventListener('click', function(){
        modalContainer.classList.add('show');
        console.log("Hello there");
    });

    clodeModal.addEventListener('click', function(){
        modalContainer.classList.remove('show');
    });

</script>
@endpush
