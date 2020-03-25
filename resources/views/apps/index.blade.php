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
            <div style="padding-left: 20px; padding-right: 20px; padding-top: 10px; padding-bottom: 10px;">
                <div style="display: flex; align-items: center;">
                    @svg('chevron-down', '#000000')

                    <h1 style="margin-left: 10px">Revoked Apps</h1>
                </div>

                <table class="table">
                    <thead style="border-bottom: 1px solid #000000;">
                        <tr>
                            <th style="text-align: left;">App name</th>
                            <th style="text-align: left;">Reason</th>
                            <th style="text-align: left;">Callback URL</th>
                            <th style="text-align: left;">Date created</th>
                            <th style="text-align: left;">Status</th>
                            <th style="text-align: left;">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>App name v2.0.1</th>
                            <th>Lorem ipsum dolor sit amet, consetetur.</th>
                            <th>https://www.appdomain.co.za</th>
                            <th>20 Feb 2019</th>
                            <th>Mark</th>
                            <th>Otto</th>
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
