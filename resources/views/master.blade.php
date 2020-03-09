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
<body>
    <x-header/>
    <main id="main">
        <button class="blue arrow-right after">button text</button>
        <button class="outline plus before">hello world</button>
        <button class="fab outline plus"></button>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptate recusandae ratione, deserunt blanditiis praesentium explicabo nobis molestias dignissimos perferendis ipsa voluptatem deleniti repellat nesciunt facilis sed, eligendi minima fugit voluptatum?</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptate recusandae ratione, deserunt blanditiis praesentium explicabo nobis molestias dignissimos perferendis ipsa voluptatem deleniti repellat nesciunt facilis sed, eligendi minima fugit voluptatum?</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptate recusandae ratione, deserunt blanditiis praesentium explicabo nobis molestias dignissimos perferendis ipsa voluptatem deleniti repellat nesciunt facilis sed, eligendi minima fugit voluptatum?</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptate recusandae ratione, deserunt blanditiis praesentium explicabo nobis molestias dignissimos perferendis ipsa voluptatem deleniti repellat nesciunt facilis sed, eligendi minima fugit voluptatum?</p>
    </main>
    <x-footer/>
    @yield("content")
    <script src="/js/scripts.js"></script>
    @stack("scripts")
</body>
</html>