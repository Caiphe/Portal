<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield("title", '{"MTN":"Developer Portal"}')</title>
    <link rel="stylesheet" href="{{ mix('/css/styles.css') }}">
    @stack("styles")
    <link rel="preload" href="/fonts/MTNBrighterSans-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Bold.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Medium.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Light.woff2" as="font" type="font/woff2">
</head>
<body style="margin: 40px;">
    <h1>Y'ello there!</h1>
    <p>This is some text to see if it is the correct size.</p>
    <button class="outline arrow-right after">hello world</button>
    <button class="blue arrow-left before">hello world</button>
    <button class="blue fab plus"></button>
    <input type="checkbox" name="flip" id="man">
    <x-switch name="hello" id="that-1"/>
    <x-switch name="hello" id="that-2" type="small"/>
    <x-switch name="hello" id="that-3" type="small" scheme="light"/>
    {{-- <x-icon icon="plus"/> --}}
    <x-footer/>
    @yield("content")
    @stack("scripts")
</body>
</html>