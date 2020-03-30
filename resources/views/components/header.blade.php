<header id="header">
    <div class="header-inner">
        <a href="/"><img src="/images/mtn-logo.svg" alt="MTN logo"></a>
        <div class="site-name">Developer Portal</div>
        <ul class="main-menu" role="navigation" aria-label="Main">
            <li><a href="/">Products</a></li>
            <li><a href="/">Insights</a></li>
            <li><a href="/">FAQ</a></li>
        </ul>
        <input type="search" name="search" id="search" class="thin see-through" placeholder="Search">
        @if(\Auth::check())
        <button class="dark">Build app</button>
        <button id="user-profile-image" class="fab image" style="background-image: url({{\Auth::user()->profile_picture}})"></button>
        @else
        <a href="{{route('login')}}" class="button dark outline mr-1">Login</a>
        <a href="{{route('register')}}" class="button dark">Register</a>
        @endif
    </div>
</header>
<div id="profile-menu-background"></div>
<ul id="profile-menu" class="shadow">
    <li><a href="/profile">Profile</a></li>
    <li><a href="/apps">Apps</a></li>
    <li><form action="{{route('logout')}}" method="post">@csrf<button>Sign out</button></form></li>
</ul>