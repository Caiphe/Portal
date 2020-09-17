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
<body class="admin">
    @yield('banner')
    <div class="wrapper">
        <nav id="sidebar">
            <ul>
                <li class="has-children  @if(!Request::is('admin/dashboard') && !Request::is('admin/users') && !Request::is('admin/users/*')) active @endif">
                    Manage
                    <ul>
                        <li @if(Request::is('admin/faqs') || Request::is('admin/faqs/*')) class="active" @endif><a href="{{ route('admin.faq.index') }}">FAQ</a></li>
                        <li @if(Request::is('admin/pages') || Request::is('admin/pages/*')) class="active" @endif><a href="{{ route('admin.page.index') }}">Pages</a></li>
                        <li @if(Request::is('admin/bundles') || Request::is('admin/bundles/*')) class="active" @endif><a href="{{ route('admin.bundle.index') }}">Bundles</a></li>
                        <li @if(Request::is('admin/products') || Request::is('admin/products/*')) class="active" @endif><a href="{{ route('admin.product.index') }}">Products</a></li>
                        <li @if(Request::is('admin/categories') || Request::is('admin/categories/*')) class="active" @endif><a href="{{ route('admin.category.index') }}">Categories</a></li>
                        <li @if(Request::is('admin/docs') || Request::is('admin/docs/*')) class="active" @endif><a href="{{ route('admin.doc.index') }}">Documentation</a></li>
                    </ul>
                </li>
                <li @if(Request::is('admin/dashboard')) class="active" @endif><a href="{{ route('admin.dashboard.index') }}">Applications</a></li>
                <li @if(Request::is('admin/users') || Request::is('admin/users/*')) class="active" @endif><a href="{{ route('admin.user.index') }}">Users</a></li>
            </ul>

            <button id="sync" class="button yellow outline" onclick="sync(this)">Sync @svg('sync', '#FC0')</button>
        </nav>
        <main id="main">
            <x-admin.header heading="Title"/>
            <section>@yield("content")</section>
        </main>
    </div>
    <x-alert/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.1/highlight.min.js"></script>
    <script>
        function bladeLookup(key) {
            return {
                syncApiUrl: "{{ route('api.sync') }}",
            }[key] || null;
        }
    </script>
    <script src="{{ mix('/js/templates/admin/scripts.js') }}"></script>
    @stack("scripts")
</body>
</html>
