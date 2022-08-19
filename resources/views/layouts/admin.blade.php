@extends('layouts.base')

@php
    $user = auth()->user();
    $isAdminUser = $user->hasRole('admin');
@endphp

@section('body-class', 'admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/layouts/admin.css') }}">
@endpush

@section('body')
    <nav id="sidebar">
        <a class="logo" href="/">@svg('logo', '', '/images/') Admin Portal</a>
        <button id="hide-menu" class="reset">@svg('close')</button>

        <ul class="main-menu">
            <li @class(['menu-applications', 'active' => Request::is('admin/dashboard') || Request::is('admin/apps/create') || Request::is('admin/apps/create/*')])><a href="{{ route('admin.dashboard.index') }}">@svg('applications') Applications</a></li>
            <li @class(['menu-products', 'active' => (Request::is('admin/products') || Request::is('admin/products/*'))])><a href="{{ route('admin.product.index') }}">@svg('products') Products</a></li>
            @if($isAdminUser)
            <li @class(['menu-products', 'active' => (Request::is('admin/tasks'))])><a href="{{ route('admin.task.index') }}">@svg('task') Tasks</a></li>
            @endif
            <li @class(['menu-users', 'active' => (Request::is('admin/users') || Request::is('admin/users/*'))])><a href="{{ route('admin.user.index') }}">@svg('users') Users</a></li>
            <li @class(['menu-faq', 'active' => (Request::is('admin/faqs') || Request::is('admin/faqs/*'))])><a href="{{ route('admin.faq.index') }}">@svg('faq') FAQ</a></li>
            <li @class(['menu-pages', 'active' => (Request::is('admin/pages') || Request::is('admin/pages/*'))])><a href="{{ route('admin.page.index') }}">@svg('pages') Pages</a></li>
            <li @class(['menu-categories', 'active' => (Request::is('admin/categories') || Request::is('admin/categories/*'))])><a href="{{ route('admin.category.index') }}">@svg('categories') Categories</a></li>
            <li @class(['menu-documentation', 'active' => (Request::is('admin/docs') || Request::is('admin/docs/*'))])><a href="{{ route('admin.doc.index') }}">@svg('documentation') Documentation</a></li>
            <li @class(['menu-bundles', 'active' => (Request::is('admin/bundles') || Request::is('admin/bundles/*'))])><a href="{{ route('admin.bundle.index') }}">@svg('bundles') Bundles</a></li>
        </ul>

        <ul class="secondary-menu">
            <li><a href="{{ route('user.profile') }}"><div class="profile-picture" style="background-image: url({{ $user->profile_picture }})"></div> {{ $user->full_name }}</a></li>
            <li class="notification-menu">
                <a class="toggle-notification">@svg('notifications') Notifications</a>
                <div class="notification-count"></div>
            </li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button>@svg('signout') Sign out</button>
                </form>
            </li>
            <li>
                <form action="{{ route('admin.search') }}" class="admin-search-form">
                    <input class="admin-search" type="text" name="q" placeholder="Search site">
                </form>
            </li>
            <li>
                @if($user->can('administer-products'))
                <button id="sync" class="button yellow outline" onclick="syncProductsThenApps()">Sync all</button>
                @endif
            </li>
        </ul>
    </nav>
    <header id="mobile-header">
        <a class="logo" href="/">@svg('logo', '', '/images/') Admin Portal</a>
        <button id="menu-button" class="reset">@svg('menu')</button>
    </header>
    
    <x-notifications></x-notifications>
    
    <main id="main">
        @yield("content")
    </main>
    <x-alert/>
@endsection

@prepend('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.1/highlight.min.js" defer></script>
    <script>
        function bladeLookupAdmin(key) {
            return {
                syncApiUrl: "{{ route('api.sync') }}",
                syncAppApiUrl: "{{ route('api.sync.apps') }}",
                syncProductApiUrl: "{{ route('api.sync.products') }}",
            }[key] || null;
        }

        // fetch("{{ route('notifications.count') }}").then(function(data){
        //     return data.json();
        // }).then(function(data){
        //     console.log(data.count);
        //     document.querySelector('.notification-count').textContent = data.count;
        // }).catch(function(error) {
        //     // 
        // });

        var request = new XMLHttpRequest();
        request.onload = requestListener;
        request.onerror = requestError;
        request.open('GET', "{{ route('notifications.count') }}", true);
        request.send();

        function requestListener(){
            var data = JSON.parse(this.responseText);
            document.querySelector('.notification-count').textContent = data.count;
        }

        function requestError(){
            console.log('Error here');
        }

    </script>
    <script src="{{ mix('/js/templates/admin/scripts.js') }}" defer></script>
@endprepend
