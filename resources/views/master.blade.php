<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield("title", '{"MTN":"Developer Portal"}')</title>
    <link rel="icon" href="/images/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="{{ mix('/css/styles.css') }}">
    @stack("styles")
    <link rel="preload" href="/fonts/MTNBrighterSans-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Bold.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Medium.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Light.woff2" as="font" type="font/woff2">
</head>
<body>
    <x-header/>
    <main id="main">
        @php
            $list = [ 
                'Adverstising' => [
                    ['label' => 'APN', 'link' => '#anchor-id'],
                    ['label' => 'Devices','link' => 'https://www.google.com/'],
                    ['label' => 'KYC','link' => 'https://www.google.com/']],
                'Customer' => [
                    ['label' => 'APN', 'link' => 'https://www.google.com/'],
                    ['label' => 'Devices','link' => 'https://www.google.com/'],
                    ['label' => 'KYC','link' => 'https://www.google.com/']],
                'Messaging' => [
                    ['label' => 'APN', 'link' => 'https://www.google.com/'],
                    ['label' => 'Devices','link' => 'https://www.google.com/'],
                    ['label' => 'KYC','link' => 'https://www.google.com/']],
                'Tickets' => [
                    ['label' => 'APN', 'link' => 'https://www.google.com/'],
                    ['label' => 'Devices','link' => 'https://www.google.com/'],
                    ['label' => 'KYC','link' => 'https://www.google.com/']],
            ];
        @endphp
        <x-sidebar-accordion id="test" :list="$list"/>
        @yield("content")
    </main>
    <x-footer/>
    <script src="/js/scripts.js"></script>
    @stack("scripts")
</body>
</html>