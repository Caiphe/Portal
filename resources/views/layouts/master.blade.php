@extends('layouts.base')

@section('body-class', 'layout-master')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/styles.css') }}">
@endpush

@section('body')
    <x-header/>
    <main id="main">
        @yield("content")
    </main>
    <x-footer/>
    <x-alert/>
@endsection

@push('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.1/highlight.min.js" defer></script>
@endpush