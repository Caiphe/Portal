<header id="header">
    <nav class="header-inner container">
        <a class="site-name" href="/"><img src="/images/mtn-logo.svg" alt="MTN logo"> Developer Portal</a>
        <ul class="main-menu" role="navigation" aria-label="Main">
            <li class="has-children">
                <a href="/products">Products @svg("chevron-down")</a>
                <div class="product-nav shadow">
                    <div class="nav-categories">
                        <h3>BROWSE BY CATEGORY</h3>
                        @foreach($globalCategories as $category)
                            <a href="{{ route('category.show', $category->slug) }}">
                                @svg($category->slug) {{$category->title}}
                                <span>{{ $category->description }}</span>
                            </a>
                        @endforeach
                    </div>
                    <div class="nav-pages">
                        <a href="/products">Browse all our products</a>
                        <a href="/bundles">Browse all our bundles</a>
                        <a href="/getting-started">Working with our products</a>
                    </div>
                </div>
            </li>
            <li><a href="/getting-started">Docs</a></li>
            <li><a href="/faq">FAQ</a></li>
            <li><a href="/contact">Contact us</a></li>
            @can('view-admin')
            <li><a href="/admin">Admin</a></li>
            @endcan
        </ul>
        <form action="{{route('search')}}">
            <input type="search" name="q" id="search" class="thin see-through" placeholder="Search">
            @svg('search')
        </form>
        @if(\Auth::check())
            <a href="/apps/create" class="button dark" role="button">Build app</a>
            <div id="profile-menu">
                <div id="profile-menu-picture" style="background-image: url({{\Auth::user()->profile_picture}})"></div>
                <ul class="profile-menu-options shadow">
                    <li><a href="/profile">Profile</a></li>
                    <li><a href="/apps">Apps</a></li>
                    <li><form action="{{route('logout')}}" method="post">@csrf<button>Sign out</button></form></li>
                </ul>
            </div>
        @else
            <a href="{{route('login')}}" class="button dark outline mr-1" role="button">Login</a>
            <a href="{{route('register')}}" class="button dark" role="button">Register</a>
        @endif
    </nav>
</header>
