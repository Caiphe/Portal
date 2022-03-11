<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.google-analytics')
    {{-- @includeWhen(!isset($_COOKIE['shownCookiePolicy']), 'partials.cookie') --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield("title", 'MTN Developer Portal')</title>
    <link rel="icon" href="/images/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/images/favicon.png" type="image/png">
    @stack("styles")
    <link rel="preload" href="/fonts/MTNBrighterSans-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Bold.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Medium.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Light.woff2" as="font" type="font/woff2">

    <meta name="title" content="@yield("title", "MTN Developer Portal")">
    <meta name="description" content="@yield("meta-description", "The MTN Developer Platform is a single point of access to a rich MTN developer ecosystem that brings a number of MTN services within easy reach of partners, startups, independent developers, enterprises.")">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="MTN Developer Portal">
    <meta property="og:title" content="@yield("title", "MTN Developer Portal")">
    <meta property="og:description" content="@yield("meta-description", "The MTN Developer Platform is a single point of access to a rich MTN developer ecosystem that brings a number of MTN services within easy reach of partners, startups, independent developers, enterprises.")">
    <meta property="og:url" content="{{ URL::current() }}">
    <meta property="og:image" content="@yield("meta-image", URL::to('/images/banners/meta.jpg'))">
    <meta property="twitter:image" content="@yield("meta-image", URL::to('/images/banners/meta.jpg'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image:alt" content="MTN Developer Portal">
</head>
<body class="@yield('body-class')">
    @yield('body')
    @stack("scripts")
</body>
</html>
