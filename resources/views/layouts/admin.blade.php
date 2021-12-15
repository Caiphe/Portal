@php
    $user = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    @production
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-EESWEBL5F7"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'G-EESWEBL5F7');
        </script>
    @endproduction
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
        <a class="logo" href="/"><img width="39px" height="42px" src="/images/mtn-logo.svg" alt="MTN logo"> Admin Portal</a>

        <ul class="main-menu">
            <li @if(Request::is('admin/dashboard')) class="active" @endif><a href="{{ route('admin.dashboard.index') }}">@svg('applications') Applications</a></li>
            <li @if(Request::is('admin/products') || Request::is('admin/products/*')) class="active" @endif><a href="{{ route('admin.product.index') }}">@svg('products') Products</a></li>
            <li @if(Request::is('admin/users') || Request::is('admin/users/*')) class="active" @endif><a href="{{ route('admin.user.index') }}">@svg('users') Users</a></li>
            <li @if(Request::is('admin/faqs') || Request::is('admin/faqs/*')) class="active" @endif><a href="{{ route('admin.faq.index') }}">@svg('faq') FAQ</a></li>
            <li @if(Request::is('admin/pages') || Request::is('admin/pages/*')) class="active" @endif><a href="{{ route('admin.page.index') }}">@svg('pages') Pages</a></li>
            <li @if(Request::is('admin/categories') || Request::is('admin/categories/*')) class="active" @endif><a href="{{ route('admin.category.index') }}">@svg('categories') Categories</a></li>
            <li @if(Request::is('admin/docs') || Request::is('admin/docs/*')) class="active" @endif><a href="{{ route('admin.doc.index') }}">@svg('documentation') Documentation</a></li>
            <li @if(Request::is('admin/bundles') || Request::is('admin/bundles/*')) class="active" @endif><a href="{{ route('admin.bundle.index') }}">@svg('bundles') Bundles</a></li>
        </ul>

        <ul class="secondary-menu">
            <li><a href="{{ route('user.profile') }}"><div class="profile-picture" style="background-image: url({{ $user->profile_picture }})"></div> {{ $user->full_name }}</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button>@svg('signout') Sign Out</button>
                </form>
            </li>
            <li>
                <form action="{{ route('admin.search') }}" class="admin-search-form">
                    <input class="admin-search" type="text" name="q" placeholder="Search site">
                </form>
            </li>
            <li>
                @if($user->can('administer-products'))
                <button id="sync" class="button yellow outline" onclick="syncProductsThenApps()">Sync All</button>
                @endif
            </li>
        </ul>
    </nav>
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
