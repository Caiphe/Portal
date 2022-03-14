@extends('layouts.base')

@section('body-class', 'layout-sidebar')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/styles.css') }}">
    <link rel="preload" href="/fonts/MTNBrighterSans-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Bold.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Medium.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Light.woff2" as="font" type="font/woff2">
@endpush

@section('body')
    <x-header/>
    @yield('banner')
    <div class="wrapper container">
        <nav id="sidebar">
            @yield('sidebar')
        </nav>
        <main id="main">
            @yield("content")
        </main>
    </div>
    <x-footer/>
    <x-alert/>
@endsection

@push('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.1/highlight.min.js" defer></script>
@endpush