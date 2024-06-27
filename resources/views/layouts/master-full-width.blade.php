@extends('layouts.base')

@section('body-class', 'layout-master-full-width')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/styles.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/layouts/cookie-notice.css') }}">
    <link rel="preload" href="/fonts/MTNBrighterSans-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Bold.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Medium.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Light.woff2" as="font" type="font/woff2">
@endpush

@section('body')
    <x-header/>
    <main id="main" class="@yield('main-class', 'default')">
        @yield("content")
    </main>
    <x-footer/>
    <x-alert/>
@endsection

@push('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.1/highlight.min.js"></script>
    <script src="{{ mix('/js/scripts.js') }}" defer></script>
@endpush
