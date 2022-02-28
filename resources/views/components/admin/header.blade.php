@props(['heading'])

<header id="header">
    <nav class="header-inner container">
        <a class="site-name" href="/">@svg('logo', '', '/images') Developer Portal</a>
        <div class="spacer"></div>
        <form action="{{route('admin.search')}}">
            <input type="search" name="q" id="search" class="thin" placeholder="Search whole site...">
        </form>
        <div id="profile-menu">
            <div id="profile-menu-picture" style="background-image: url({{\Auth::user()->profile_picture}})"></div>
            <ul class="profile-menu-options shadow">
                <li><a href="{{ route('user.profile') }}">My profile</a></li>
                <li><a href="{{ route('app.store') }}">My apps</a></li>
                <li><a href="{{ route('teams.listing') }}">My teams</a></li>
                <li><form action="{{route('logout')}}" method="post">@csrf<button>Sign out</button></form></li>
            </ul>
        </div>
    </nav>
</header>
<div class="header-page-info cols centre-align">
    <h1 class="page-header-title">@yield('title')
        @if( request()->is('users/single'))
            <a class="button outline dark" href="{{ route('app.create') }}">Create an app for this user</a>
        @endif

    </h1>
    <div class="page-info">@yield('page-info')</div>
</div>
