@php
    $user = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.google-analytics')
    {{-- @includeWhen(!isset($_COOKIE['shownCookiePolicy']), 'partials.cookie') --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield("title", '{"MTN":"Developer Admin Portal"}')</title>
    <link rel="icon" href="/images/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="{{ mix('/css/layouts/admin.css') }}">
    @stack("styles")
</head>
<body class="admin">
    <nav id="sidebar">
        <a class="logo" href="/">@svg('logo', '', '/images/') Admin Portal</a>
        <button id="hide-menu" class="reset">@svg('close')</button>

        <ul class="main-menu">
            <li @class(['menu-applications', 'active' => Request::is('admin/dashboard')])><a href="{{ route('admin.dashboard.index') }}">@svg('applications') Applications</a></li>
            <li @class(['menu-products', 'active' => (Request::is('admin/products') || Request::is('admin/products/*'))])><a href="{{ route('admin.product.index') }}">@svg('products') Products</a></li>
            <li @class(['menu-users', 'active' => (Request::is('admin/users') || Request::is('admin/users/*'))])><a href="{{ route('admin.user.index') }}">@svg('users') Users</a></li>
            <li @class(['menu-faq', 'active' => (Request::is('admin/faqs') || Request::is('admin/faqs/*'))])><a href="{{ route('admin.faq.index') }}">@svg('faq') FAQ</a></li>
            <li @class(['menu-pages', 'active' => (Request::is('admin/pages') || Request::is('admin/pages/*'))])><a href="{{ route('admin.page.index') }}">@svg('pages') Pages</a></li>
            <li @class(['menu-categories', 'active' => (Request::is('admin/categories') || Request::is('admin/categories/*'))])><a href="{{ route('admin.category.index') }}">@svg('categories') Categories</a></li>
            <li @class(['menu-documentation', 'active' => (Request::is('admin/docs') || Request::is('admin/docs/*'))])><a href="{{ route('admin.doc.index') }}">@svg('documentation') Documentation</a></li>
            <li @class(['menu-bundles', 'active' => (Request::is('admin/bundles') || Request::is('admin/bundles/*'))])><a href="{{ route('admin.bundle.index') }}">@svg('bundles') Bundles</a></li>
        </ul>

        <ul class="secondary-menu">
            <li><a href="{{ route('user.profile') }}"><div class="profile-picture" style="background-image: url({{ $user->profile_picture }})"></div> {{ $user->full_name }}</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button>@svg('signout') Sign out</button>
                </form>
            </li>
            <li>
                <form action="{{ route('admin.search') }}" class="admin-search-form">
                    <input class="admin-search" type="text" name="q" placeholder="Search site">
                </form>
            </li>
            <li>
                @if($user->can('administer-products'))
                <button id="sync" class="button yellow outline" onclick="syncProductsThenApps()">Sync all</button>
                @endif
            </li>
        </ul>
    </nav>
    <header id="mobile-header">
        <a class="logo" href="/">@svg('logo', '', '/images/') Admin Portal</a>
        <button id="menu-button" class="reset">@svg('menu')</button>
    </header>
    <main id="main">
        @yield("content")
    </main>
    <x-alert/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.1/highlight.min.js"></script>
    <script>
        function bladeLookupAdmin(key) {
            return {
                syncApiUrl: "{{ route('api.sync') }}",
                syncAppApiUrl: "{{ route('api.sync.apps') }}",
                syncProductApiUrl: "{{ route('api.sync.products') }}",
            }[key] || null;
        }
    </script>
    <script src="{{ mix('/js/templates/admin/scripts.js') }}"></script>
    @stack("scripts")
</body>
</html>
