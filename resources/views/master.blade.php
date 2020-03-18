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

    <style>
        .main-carousel {
            width: 300px;
            height: 500px;
            background-color: blue;
        }
    </style>
</head>
<body>
    <x-header/>
    <main id="main">
        @yield("content")
        <x-carousel class="main-carousel" wait="4000" duration="1">
            <x-carousel-item style="background: linear-gradient(90deg, rgba(255,204,0,1) 0%, rgba(0,102,0,1) 100%);">This is an item 1</x-carousel-item>
            <x-carousel-item style="background: linear-gradient(90deg, rgba(0,20,102,1) 0%, rgba(0,102,0,1) 100%);">This is an item 2</x-carousel-item>
            <x-carousel-item style="background: linear-gradient(90deg, rgba(102,0,0,1) 0%, rgba(0,102,0,1) 100%);">This is an item 3</x-carousel-item>
        </x-carousel>
    </main>
    <x-footer/>
    <script src="/js/scripts.js"></script>
    @stack("scripts")
</body>
</html>