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
        #feature{
            width: 300px;
            height: 500px;
            background-color: grey;
        }
    </style>
</head>
<body>
    <x-header/>
    <main id="main">
        <x-carousel id="feature" wait="5000" duration="0.34">
            <x-carousel-item>
                <h2>One</h2>
                <p>This is some content.</p>
            </x-carousel-item>
            <x-carousel-item>
                <h2>Two</h2>
                <p>This is some content.</p>
            </x-carousel-item>
            <x-carousel-item>
                <h2>Three</h
                    2><p>This is some content.</p>
            </x-carousel-item>
            <x-carousel-item>
                <h2>Four</h2
                    ><p>This is some content.</p>
            </x-carousel-item>
        </x-carousel>
        @yield("content")
    </main>
    <x-footer/>
    <script src="/js/scripts.js"></script>
    @stack("scripts")
</body>
</html>