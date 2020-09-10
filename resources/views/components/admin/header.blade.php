@props(['heading'])

<header id="header">
    <nav class="header-inner container">
        <h1>@yield('title')</h1>
        <div class="spacer"></div>
        <form action="{{route('admin.search')}}">
            <input type="search" name="q" id="search" class="thin see-through" placeholder="Search">
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
