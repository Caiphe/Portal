<header id="header">
    <nav class="header-inner container">
        <a class="site-name" href="/"><img src="/images/mtn-logo.svg" alt="MTN logo"> Developer Portal</a>
        <button type="button" class="menu-button">
            <svg id="open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <svg id="close" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        <ul class="main-menu hidden" role="navigation" aria-label="Main">
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
            @can('view-dashboard')
            <li><a href="/dashboard">Dashboard</a></li>
            @endcan
        </ul>
        <form class="hidden" action="{{route('search')}}">
            <input type="search" name="q" id="search" class="thin see-through" placeholder="Search">
            @svg('search')
        </form>
        @if(\Auth::check())
            <a href="/apps/create" class="button dark hidden" role="button">Build app</a>
            <div id="profile-menu hidden">
                <div id="profile-menu-picture hidden" style="background-image: url({{\Auth::user()->profile_picture}})"></div>
                <ul class="profile-menu-options shadow">
                    <li><a href="/profile">Profile</a></li>
                    <li><a href="/apps">Apps</a></li>
                    <li><form action="{{route('logout')}}" method="post">@csrf<button>Sign out</button></form></li>
                </ul>
            </div>
        @else
            <a href="{{route('login')}}" class="button dark outline mr-1 hidden" role="button">Login</a>
            <a href="{{route('register')}}" class="button dark hidden" role="button">Register</a>
        @endif
    </nav>
    <!-- Mobile Menu -->
    <ul class="mobile-menu container">
        <li>
            <a href="/products">Products @svg("chevron-down")</a>
        </li>
        <li>
            <a href="/bundles">Bundles</a>
        </li>
        <li>
            <a href="/getting-started">Docs</a>
        </li>
        <li>
            <a href="/faq">FAQ</a>
        </li>
        <li>
            <a href="/contact">Contact us</a>
        </li>
        @if(\Auth::check())
        <li>
            <a href="/apps/create">Build app</a>
        </li>
        <li>
            <a href="/profile">Profile</a></li>
        <li>
            <a href="/apps">Apps</a>
        </li>
        <li>
            <form action="{{route('logout')}}" method="post">@csrf<button>Sign out</button></form>
        </li>
        @else
        <li>
            <a href="{{route('login')}}" role="button">Login</a>
        </li>
        <li>
            <a href="{{route('register')}}" role="button">Register</a>
        </li>
        @endif
    </ul>
</header>

<script>
document.querySelector('.menu-button').addEventListener('click', function(e) {
    document.querySelector('.mobile-menu').classList.toggle('active');
    document.getElementById('close').classList.toggle('block');
    document.getElementById('open').classList.toggle('hidden');
});
</script>
