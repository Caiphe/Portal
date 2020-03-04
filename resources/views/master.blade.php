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
    <input type="text" name="name" id="id" placeholder="Hhi there"><br>
    <input type="text" name="name" id="id" class="thin" placeholder="Hhi there"><br>
    <input type="text" name="name" id="id" class="alt" placeholder="Hhi there"><br>
    <select name="name" id="id">
        <option value="one">one</option>
        <option value="one">one</option>
        <option value="one">one</option>
        <option value="one">one</option>
        <option value="one">one</option>
        <option value="one">one</option>
        <option value="one">one</option>
    </select><br>
    <textarea name="t" id="jkl" rows="5" placeholder="Enter callback url"></textarea><br>
    <input type="checkbox" value="yes">
    <button class="fab plus"></button>
    <button>Hello world</button>
    <button class="end arrow-right">Hello world</button>
    <button class="front arrow-left">Hello world</button>
    <button class="blue fab plus"></button>
    <button class="blue">Hello world</button>
    <button class="blue outline">Hello world</button>
    <button class="blue outline front plus">Hello world</button>
    <button class="blue end arrow-right">Hello world</button>
    <button class="outline fab plus"></button>
    <button class="outline blue fab plus"></button>
    <button class="outline">Hello world</button>
    <button class="dark fab plus"></button>
    <button class="dark">Hello world</button>
    <button class="dark end arrow-right">Hello world</button>
    <button class="medium fab plus"></button>
    <button class="medium">Hello world</button>
    <button class="medium end arrow-right">Hello world</button>
    @yield("content")
    @stack("scripts")
</body>
</html>