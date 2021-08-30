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
    My teams
@endsection

@section('content')
    <x-heading heading="My teams" tags="Dashboard">
        <a href="{{ route('teams.create') }}" class="button dark outline">Creare New</a>
    </x-heading>

    <div class="mt-2">
        <div class="column">
            <table class="teams">
                <tr class="table-title">
                    <td class="bold">Team Name @svg('arrow-down' ,'#cdcdcd')</td>
                    <td class="bold">Country @svg('arrow-down' ,'#cdcdcd')</td>
                    <td class="bold">Members @svg('arrow-down' ,'#cdcdcd')</td>
                    <td class="bold">Apps @svg('arrow-down' ,'#cdcdcd')</td>
                    <td>&nbsp;</td>
                </tr>
                @foreach($teams as $team)
                    <tr>
                        <td class="company-logo-name">
                            <div class="company-logo " style="background-image: url('/images/no-logo.svg')"></div>
                            <a class="company-name-a bold" href="{{route('team.show', [ 'id' => $team->id ])}}">{{ $team->name }}</a>
                        </td>
                        <td>{{ $team->country }}</td>
                        <td>0</td>
                        <td>0</td>
                        <td>
                            <a href="" class="button red-button">LEAVE</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

    </script>
@endpush
