@props(['heading'])

<header id="header">
    <nav class="header-inner container">
        <a class="logo" href="/"><img src="/images/mtn-logo.svg" alt="MTN logo"> Developer Portal</a>
        <div class="spacer"></div>
        <form action="{{route('admin.search')}}">
            <input type="search" name="q" id="search" class="thin" placeholder="Search">
        </form>
        <div id="profile-menu">
            <div id="profile-menu-picture" style="background-image: url({{\Auth::user()->profile_picture}})"></div>
            <ul class="profile-menu-options shadow">
                <li><a href="/profile">Profile</a></li>
                <li><a href="/apps">Apps</a></li>
                <li><form action="{{route('logout')}}" method="post">@csrf<button>Sign out</button></form></li>
            </ul>
        </div>
    </nav>
</header>
<div class="header-page-info cols centre-align">
    <h1>@yield('title')</h1>
    <div class="spacer-flex"></div>
    <div class="page-info">@yield('page-info')</div>
</div>
