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
            <div>
                <h1>Approved Apps</h1>

                <x-app title="Test"></x-app>

{{--                --}}
{{--            @forelse($apps['app'] as $app)--}}
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
                    <thead style="border-bottom: 1px solid #000000;">
                        <tr>
                            <th style="text-align: left; border-bottom: 2px solid #dee2e6;">App name</th>
                            <th style="text-align: left; border-bottom: 2px solid #dee2e6;">Reason</th>
                            <th style="text-align: left; border-bottom: 2px solid #dee2e6;">Callback URL</th>
                            <th style="text-align: left; border-bottom: 2px solid #dee2e6;">Date created</th>
                            <th style="text-align: left; border-bottom: 2px solid #dee2e6;">Status</th>
                            <th style="text-align: left; border-bottom: 2px solid #dee2e6;">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th style="text-align: left; padding-top: 10px;">App name v2.0.1</th>
                            <th style="text-align: left; padding-top: 10px; font-size: 12px;">Lorem ipsum dolor sit amet, consetetur.</th>
                            <th style="text-align: left; padding-top: 10px; font-size: 12px;">https://www.appdomain.co.za</th>
                            <th style="text-align: left; padding-top: 10px; font-size: 12px;">20 Feb 2019</th>
                            <th style="text-align: left; padding-top: 10px; font-size: 12px;">
                                <svg height="100" width="100">
                                    <circle cx="50" cy="50" r="40" stroke="black" stroke-width="3" fill="red" />
                                </svg>
                            </th>
                            <th style="text-align: left; padding-top: 10px; font-size: 12px;">Otto</th>
                        </tr>
                    </tbody>
                </table>
                {{--                --}}
                {{--            @forelse($apps['app'] as $app)--}}
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
    </div>

@endsection
