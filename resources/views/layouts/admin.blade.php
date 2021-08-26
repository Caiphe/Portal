<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield("title", '{"MTN":"Developer Admin Portal"}')</title>
    <link rel="icon" href="/images/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="{{ mix('/css/styles.css') }}">
    @stack("styles")
</head>
<body class="admin @yield('body-class')">
    @yield('banner')
    <div class="wrapper">
        <nav id="sidebar">
            <ul>
                @if(\Auth::user()->can('administer-content') || \Auth::user()->can('administer-products'))
                <li class="has-children  @if(!Request::is('admin/dashboard') && !Request::is('admin/users') && !Request::is('admin/users/*')) active @endif">
                    Manage
                    <ul>
                        @if(\Auth::user()->can('administer-content'))
                        <li @if(Request::is('admin/faqs') || Request::is('admin/faqs/*')) class="active" @endif><a href="{{ route('admin.faq.index') }}">FAQ</a></li>
                        <li @if(Request::is('admin/pages') || Request::is('admin/pages/*')) class="active" @endif><a href="{{ route('admin.page.index') }}">Pages</a></li>
                        <li @if(Request::is('admin/categories') || Request::is('admin/categories/*')) class="active" @endif><a href="{{ route('admin.category.index') }}">Categories</a></li>
                        <li @if(Request::is('admin/docs') || Request::is('admin/docs/*')) class="active" @endif><a href="{{ route('admin.doc.index') }}">Documentation</a></li>
                        @endif
                        @if(\Auth::user()->can('administer-products'))
                        <li @if(Request::is('admin/bundles') || Request::is('admin/bundles/*')) class="active" @endif><a href="{{ route('admin.bundle.index') }}">Bundles</a></li>
                        <li @if(Request::is('admin/products') || Request::is('admin/products/*')) class="active" @endif><a href="{{ route('admin.product.index') }}">Products</a></li>
                        @endif
                            <li><a href="/teams">Teams</a></li>
                    </ul>
                </li>
                @endif
                @if(\Auth::user()->can('administer-dashboard'))
                <li @if(Request::is('admin/dashboard')) class="active" @endif><a href="{{ route('admin.dashboard.index') }}">Applications</a></li>
                @endif
                @if(\Auth::user()->can('administer-users'))
                <li @if(Request::is('admin/users') || Request::is('admin/users/*')) class="active" @endif><a href="{{ route('admin.user.index') }}">Users</a></li>
                @endif
            </ul>

            @if(\Auth::user()->can('administer-products'))
            <button id="sync" class="button yellow outline" onclick="sync(this)">Sync All @svg('sync', '#FC0')</button>
            @endif
        </nav>
        <main id="main">
            <x-admin.header heading="Title"/>
            <section>@yield("content")</section>
        </main>
    </div>
    <x-alert/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.1/highlight.min.js"></script>
    <script>
        function bladeLookupAdmin(key) {
            return {
                syncApiUrl: "{{ route('api.sync') }}",
                syncProductApiUrl: "{{ route('api.sync.products') }}",
            }[key] || null;
        }
    </script>
    <script src="{{ mix('/js/templates/admin/scripts.js') }}"></script>
    @stack("scripts")
</body>
</html>
