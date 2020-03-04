<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield("title", '{"MTN":"Developer Portal"}')</title>
    <link rel="stylesheet" href="{{ mix('/css/styles.css') }}">
    @stack("styles")
</head>
<body>
    <button class="outline front arrow-left">Hello</button>
    <button class="end arrow-right">Hello</button>
    <button class="dark end arrow-right">Hello</button>
    <button class="dark icon-only plus"></button>
    <button class="icon-only plus"></button>
    <button>Hi</button>
    @yield("content")
    @stack("scripts")
</body>
</html>