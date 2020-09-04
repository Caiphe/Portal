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
    <x-alert/>
    @yield('banner')
    <div class="wrapper">
        <nav id="sidebar">
            <a class="logo" href="/"><img src="/images/mtn-logo.svg" alt="MTN logo"> Developer<br>Portal</a>
            <ul>
                <li>
                    Content
                    <ul>
                        <li><a href="#">Pages</a></li>
                        <li><a href="#">Bundles</a></li>
                        <li><a href="{{ route('admin.product.index') }}">Products</a></li>
                        <li><a href="#">Categories</a></li>
                        <li><a href="#">Documentation</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('dashboard') }}">Applications</a></li>
                <li><a href="#">Users</a></li>
            </ul>
        </nav>
        <main id="main">
            <x-admin.header heading="Title"/>
            <section>@yield("content")</section>
        </main>
    </div>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.1/highlight.min.js"></script>
    <script src="{{ mix('/js/scripts.js') }}"></script>
    @stack("scripts")
</body>
</html>
