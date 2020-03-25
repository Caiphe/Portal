@push('styles')
    <link rel="stylesheet" href="/css/templates/apps/_index.css">
@endpush

@extends('layouts.sidebar')

@section('sidebar')
    Sidebar content
@endsection

@section('content')

    <x-heading heading="Apps" tags="DASHBOARD">
        <button class="outline dark">Create new</button>
    </x-heading>

    <div class="container" id="app-index">
        <div class="row">
            <div style="padding-left: 20px; padding-right: 20px; padding-top: 10px; padding-bottom: 20px;">
                <div style="display: flex; align-items: center;">
                    @svg('chevron-down', '#000000')

                    <h1 style="margin-left: 10px">Approved Apps</h1>
                </div>



                <table class="table">
                    <thead class="app">
                        <tr>
                            <th>App name</th>
                            <th>Regions</th>
                            <th>Callback URL</th>
                            <th>Date created</th>
                            <th>Status</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approved_apps as $app)
                            <tr>
                                <th>
                                    {{ $app['name'] }}
                                </th>
                                <th>

                                </th>
                                <th>
                                    <a href="">https://www.appdomain.co.za</a>
                                </th>
                                <th>{{ \Carbon\Carbon::parse()->format('d M') }}</th>
                                <th>
                                    <svg height="25" width="25">
                                        <circle cx="12.5" cy="12.5" r="10" stroke="#BB1E4F" stroke-width="3" fill="#BB1E4F" />
                                    </svg>
                                </th>
                                <th>
                                    <span class="actions"></span>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
{{--                @forelse($approved_apps as $app)--}}
{{--                    <x-app :title="$app['name']"></x-app>--}}
{{--                @empty--}}
{{--                    <div class="col-12">--}}
{{--                        @svg('app', '#ffffff')--}}
{{--                        <h1>Looks like you don’t have any apps yet.</h1>--}}
{{--                        <p>Fortunately, it’s very easy to create one.</p>--}}

{{--                        <button class="outline dark">Create app</button>--}}
{{--                    </div>--}}
{{--                @endforelse--}}
            </div>
        </div>

        <div class="row">
            <div style="padding-left: 20px; padding-right: 20px; padding-top: 10px; padding-bottom: 20px;">
                <div style="display: flex; align-items: center;">
                    @svg('chevron-down', '#000000')

                    <h1 style="margin-left: 10px">Revoked Apps</h1>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>App name</th>
                            <th>Reason</th>
                            <th>Callback URL</th>
                            <th>Date created</th>
                            <th>Status</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revoked_apps as $app)
                            <tr>
                                <th>{{ $app['name'] }}</th>
                                <th>Lorem ipsum dolor sit amet, consetetur.</th>
                                <th>
                                    <a href="">https://www.appdomain.co.za</a>
                                </th>
                                <th>{{ \Carbon\Carbon::parse()->format('d M') }}</th>
                                <th>
                                    <svg height="25" width="25">
                                        <circle cx="12.5" cy="12.5" r="10" stroke="#BB1E4F" stroke-width="3" fill="#BB1E4F" />
                                    </svg>
                                </th>
                                <th>
                                    <span class="actions"></span>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
