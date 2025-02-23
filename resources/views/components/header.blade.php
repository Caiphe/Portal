<header class="parent-header">
    <x-banner  />

    <div id="header">
        <nav class="header-inner container">
            <a class="site-name" href="/">@svg('logo', '', '/images') Developer Portal</a>
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
                    <a href="{{ route('product.index') }}">Products @svg("chevron-down")</a>
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
                            <a href="{{ route('product.index') }}">Browse all our products</a>
                            <a href="{{ route('doc.index') }}">Working with our products</a>
                        </div>
                    </div>
                </li>
                <li><a href="{{ route('faq.index') }}">FAQ</a></li>
                <li><a href="{{ route('contact.index') }}">Contact us</a></li>
                @can('view-admin')
                <li><a href="{{ route('admin.home') }}">Admin</a></li>
                @endcan
            </ul>
            <form class="hidden" action="{{route('search')}}">
                <input type="search" name="q" id="search" class="thin see-through" placeholder="Search">
                @svg('search')
            </form>
            @if(\Auth::check())
                <a href="{{ route('app.create') }}" class="button dark hidden dont-shrink" role="button">Build app</a>
                <div id="profile-menu" class="hidden">
                    <div id="profile-menu-picture" class="hidden" style="background-image: url({{\Auth::user()->profile_picture}})"></div>
                    <ul class="profile-menu-options shadow">
                        <li><a href="{{ route('user.profile') }}">My profile</a></li>
                        <li><a href="{{ route('app.store') }}">My apps</a></li>
                        <li><a href="{{ route('teams.listing') }}">My teams</a></li>
                        <li><form action="{{route('logout')}}" method="post">@csrf<button>Sign out</button></form></li>
                    </ul>
                </div>

                <div id="notification-menu">
                    <div class="notification-container shadow">
                        <span class="toggle-notification"></span>
                        <span id="notificationMenu"></span>
                    </div>

                    <div class="notification-button-block">
                        <span class="notification-red-dot" id="notification-red-dot"></span>
                        <input type="hidden" class="front-notification-count" id="front-notification-count" value="" />
                        <button id="notification-btn" class="button notification-btn">@svg('notifications')</button>
                    </div>

                    {{-- Notification section --}}
                    <x-notifications></x-notifications>

                </div>


            @else
                <a href="{{route('login')}}" class="button dark outline mr-1 hidden" role="button">Login</a>
                <a href="{{route('register')}}" class="button dark hidden" role="button">Register</a>
            @endif
        </nav>
        <!-- Mobile Menu -->
        <ul class="mobile-menu container">
            <li>
                <a href="{{ route('product.index') }}">Products @svg("chevron-down")</a>
            </li>
            <li>
                <a href="{{ route('doc.index') }}">Docs</a>
            </li>
            <li>
                <a href="{{ route('faq.index') }}">FAQ</a>
            </li>
            <li>
                <a href="{{ route('contact.index') }}">Contact us</a>
            </li>
            @if(\Auth::check())
            <li>
                <a href="{{ route('app.create') }}">Build app</a>
            </li>
            <li>
                <a href="{{ route('user.profile') }}">My profile</a>
            </li>
            <li>
                <a href="{{ route('app.store') }}">My apps</a>
            </li>
            <li>
                <a href="{{ route('teams.listing') }}">My teams</a>
            </li>
            <li>
                <a href="#" class="mobile-show">Notifications</a>
            </li>
            @can('view-admin')
                <li><a href="{{ route('admin.home') }}">Admin</a></li>
            @endcan
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
    </div>
</header>

@once
@push('scripts')
<script src="{{ mix('/js/components/notification-banner.js') }}" defer></script>
<script src="{{ mix('/js/components/notifications.js') }}" defer></script>
<script src="{{ mix('/js/components/alert.js') }}" defer></script>

<script>
    document.querySelector('.menu-button').addEventListener('click', function(e) {
        document.querySelector('.mobile-menu').classList.toggle('active');
        document.getElementById('close').classList.toggle('block');
        document.getElementById('open').classList.toggle('hidden');
    });
</script>
@endpush
@endonce
