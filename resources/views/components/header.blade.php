<header id="header">
    <div class="header-inner container">
        <a href="/"><img src="/images/mtn-logo.svg" alt="MTN logo"></a>
        <div class="site-name">Developer Portal</div>
        <ul class="main-menu" role="navigation" aria-label="Main">
            <li class="has-children">
                <a href="/products">Products @svg("chevron-down")</a>
                <div class="product-nav shadow">
                    <div class="nav-left">
                        <a href="/products">Browse all our products</a>
                        <a href="/getting-started">Working with our products</a>
                    </div>
                    <div class="nav-right">
                        <h3>BROWSE BY CATEGORY</h3>
                        @foreach($productCategories as $productCategory)
                        <a href="/products/?category={{$productCategory}}">{{$productCategory}}</a>
                        @endforeach
                    </div>
                </div>
            </li>
			<li><a href="/faq">FAQ</a></li>
			<li><a href="/contact">Contact us</a></li>
        </ul>
        <input type="search" name="search" id="search" class="thin see-through" placeholder="Search">
        @if(\Auth::check())
        <a href="/apps/create" class="button dark">Build app</a>
        <div id="profile-menu">
            <div id="profile-menu-picture" style="background-image: url({{\Auth::user()->profile_picture}})"></div>
            <ul class="profile-menu-options shadow">
                <li><a href="/profile">Profile</a></li>
                <li><a href="/apps">Apps</a></li>
                <li><form action="{{route('logout')}}" method="post">@csrf<button>Sign out</button></form></li>
            </ul>
        </div>
        @else
        <a href="{{route('login')}}" class="button dark outline mr-1">Login</a>
        <a href="{{route('register')}}" class="button dark">Register</a>
        @endif
    </div>
</header>