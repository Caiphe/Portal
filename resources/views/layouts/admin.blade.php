@extends('layouts.base')

@php
    $user = auth()->user();
    $isAdminUser = $user->hasRole('admin');
@endphp

@section('body-class', 'admin')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/layouts/admin.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/layouts/cookie-notice.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/components/notification-admin.css') }}">
@endpush

@section('body')
    <nav id="sidebar">
        <a class="logo" href="/">@svg('logo', '', '/images/') Admin Portal</a>
        <button id="hide-menu" class="reset">@svg('close')</button>

        <ul class="main-menu" id="main-menu">
            <li @class(['menu-applications', 'active' => Request::is('admin/dashboard') || Request::is('admin/apps/create') || Request::is('admin/apps/create/*')])><a href="{{ route('admin.dashboard.index') }}">@svg('applications') Applications</a></li>
            <li @class(['menu-products', 'active' => (Request::is('admin/products') || Request::is('admin/products/*'))])><a href="{{ route('admin.product.index') }}">@svg('products') Products</a></li>
            <li @class(['menu-products', 'active' => (Request::is('admin/tasks'))])><a href="{{ route('admin.task.index') }}">@svg('task') Tasks</a></li>
            <li @class(['menu-users', 'active' => (Request::is('admin/users') || Request::is('admin/users/*'))])><a href="{{ route('admin.user.index') }}">@svg('users') Users</a></li>

            @can('administer-content')
                <li @class(['menu-users', 'active' => (Request::is('admin/teams') || Request::is('admin/teams/*'))])><a href="{{ route('admin.team.index') }}">@svg('teams') Teams</a></li>
                <li @class(['menu-faq', 'active' => (Request::is('admin/faqs') || Request::is('admin/faqs/*'))])><a href="{{ route('admin.faq.index') }}">@svg('faq') FAQ</a></li>
                <li @class(['menu-pages', 'active' => (Request::is('admin/pages') || Request::is('admin/pages/*'))])><a href="{{ route('admin.page.index') }}">@svg('pages') Pages</a></li>
                <li @class(['menu-categories', 'active' => (Request::is('admin/categories') || Request::is('admin/categories/*'))])><a href="{{ route('admin.category.index') }}">@svg('categories') Categories</a></li>
                <li @class(['menu-documentation', 'active' => (Request::is('admin/docs') || Request::is('admin/docs/*'))])><a href="{{ route('admin.doc.index') }}">@svg('documentation') Documentation</a></li>
                <li @class(['menu-documentation', 'active' => (Request::is('admin/settings/maintenance') || Request::is('admin/settings/maintenance/*'))])><a href="{{ route('admin.setting.maintenance') }}">@svg('setting') Settings</a></li>
            @endcan
        </ul>

        <ul class="secondary-menu">
            <li><a href="{{ route('user.profile') }}"><div class="profile-picture" style="background-image: url({{ $user->profile_picture }})"></div> {{ $user->full_name }}</a></li>
            <li class="notification-menu">
                <a class="toggle-notification">@svg('notifications') Notifications</a>
                <div class="notification-count"></div>
            </li>
            <li class="logout-menu">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="logout-button">@svg('signout') Sign out</button>
                </form>
            </li>
            <li>
                <form action="{{ route('admin.search') }}" class="admin-search-form">
                    <input class="admin-search" type="text" name="q" placeholder="Search site">
                </form>
            </li>
            <li>
                @if($user->can('administer-content'))
                <button id="sync" class="button yellow outline" onclick="createSyncJob()">Sync all</button>
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
                syncAllApiUrl: "{{ route('api.sync.all') }}",
                syncAppApiUrl: "{{ route('api.sync.apps') }}",
                syncProductApiUrl: "{{ route('api.sync.products') }}",
            }[key] || null;
        }

        var request = new XMLHttpRequest();
        request.onload = requestListener;
        request.open('GET', "{{ route('notifications.count') }}", true);
        request.send();

        function requestListener(){
            var noticationCount = document.querySelector('.notification-count');
            var data = JSON.parse(this.responseText);
            noticationCount.textContent = data.count;

            if(data.count === 0){
                noticationCount.classList.add('hide');
            }
        }

        // Toggle the active menu on notification menu click
        document.querySelector('.toggle-notification').addEventListener('click', toggleShowNotification);

        function toggleShowNotification(){
            notificationMainContainer.classList.toggle('show');
            notificationMenu.classList.toggle('active');
            var mainMenu = document.querySelector('#main-menu li.active');
            mainMenu.classList.toggle('non-active');
        }

        (function () {
            var notificationMainContainer = document.getElementById('notification-main-container');
            var markAsReadButtons = document.querySelectorAll('.mark-as-read');
            var notificationMenu = document.querySelector('.notification-menu');

            document.querySelector('.toggle-notification').addEventListener('click', toggleShowNotification);
            function toggleShowNotification(){
                notificationMainContainer.classList.toggle('show');
                notificationMenu.classList.toggle('active');
            }

        });

    </script>
    <script src="{{ mix('/js/templates/admin/scripts.js') }}" defer></script>
    <script src="{{ mix('/js/templates/admin/notifications.js') }}" defer></script>

@endprepend
