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
<x-heading heading="Teams" tags="Dashboard"></x-heading>

<div class="header-block">
    <h2>{{  $team->name }}</h2>
</div>

<div class="mt-2">
    <h5>Team bio</h5>
    <p>{{ $team->description }}</p>
    <div class="detail-left">
        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Contact number:</strong></div>
            <div class="detail-item detail-item-description">{{ $team->contact }}</div>
        </div>
        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Company URL:</strong></div>
            <div class="detail-item detail-item-description">{{ $team->url }}</div>
        </div>
        <div class="detail-row cols no-wrap">
            <div class="detail-item"><strong>Country:</strong></div>
            <div class="detail-item detail-item-description">{{ $team->country }}</div>
        </div>
    </div>
    <div class="column">
        <div class="team-members-header">
            <h2>Team membership</h2>
            <button class="outline dark">Add a teammate</button>
        </div>
    </div>
    <div class="column">
        <table class="team-members">
            <tr>
                <td>Member name</td>
                <td>Role</td>
                <td>2FA Status</td>
            </tr>
            @foreach($team->users as $teamUser)
                <tr>
                    <td>
                        {{ $teamUser->first_name }} {{ $teamUser->last_name }}
                        ({{ $teamUser->email }})
                        @if($teamUser->isTeamOwner())
                            <span>OWNER</span>
                        @endif
                    </td>
                    <td>{{ $teamUser->roles()->first()->name  === 'admin' ? 'Administrator' : ucfirst($teamUser->roles()->first()->name) }}</td>
                    <td>{{ $teamUser->twoFactorStatus() }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="column" id="app-index">
        <div class="row">
            <div class="approved-apps">
                <div class="heading-app">
                    @svg('chevron-down', '#000000')

                    <h3>Approved Apps</h3>
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
                            &nbsp;
                        </div>
                    </div>
                    <div class="body">
                        @forelse($approvedApps as $app)
                            @if(!empty($app['attributes']))
                                <x-app
                                    :app="$app"
                                    :attr="$app['attributes']"
                                    :details="$app['developer']"
                                    :countries="!is_null($app->country) ? [$app->country->code => $app->country->name] : ['globe' => 'globe']"
                                    :type="$type = 'approved'">
                                </x-app>
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

</script>
@endpush
