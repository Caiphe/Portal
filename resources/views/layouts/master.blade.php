<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    @if ($errors->any() || Session::has('alert'))
    @php
    if($errors->any()){
        $type = 'error';
        $messages = $errors->all();
    } else {
        $alert = explode(':', Session::get('alert'));
        $type = 'success';
        $messages = $alert[0];
        if(count($alert) > 1){
            $type = strtolower($alert[0]);
            $messages = $alert[1];
        }
        $messages = preg_split('/;\s?/', $messages);
    }
    @endphp
    <div id="alert" class="{{$type}}">
        <div class="container">
            <ul>
                @foreach ($messages as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
            <button class="fab blue close" onclick="closeAlert()"></button>
        </div>
    </div>
    @endif
    <main id="main">
        @yield("content")
    </main>
    <x-footer/>
    <script src="/js/scripts.js"></script>
    @stack("scripts")
</body>
</html>