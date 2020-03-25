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
                <div style="display: flex; align-items: center;">
                    @svg('chevron-down', '#000000')

                    <h1 style="margin-left: 10px">Approved Apps</h1>
                </div>

                <div class="my-apps">
                    <div class="head">
                        <div class="column">
                            <p>App name</p>
                        </div>

                        <div class="column">
                            <p>Regions</p>
                        </div>

                        <div class="column">
                            <p>Callback URL</p>
                        </div>

                        <div class="column">
                            <p>Date created</p>
                        </div>

                        <div class="column">
                            <p>Status</p>
                        </div>

                        <div class="column">
                            <p>&nbsp;</p>
                        </div>
                    </div>
                    <div class="body">
                        @foreach($approved_apps as $app)
                            <div class="my-app">
                                <div class="column">
                                    <p class="name">{{ $app['name'] }}</p>
                                </div>
                                <div class="column">
                                    Lorem ipsum dolor sit amet, consetetur.
                                </div>
                                <div class="column">
                                    <a href="">https://www.appdomain.co.za</a>
                                </div>
                                <div class="column">
                                    {{ \Carbon\Carbon::parse()->format('d M') }}
                                </div>
                                <div class="column">
                                    <svg height="25" width="25">
                                        <circle cx="12.5" cy="12.5" r="10" stroke="#BB1E4F" stroke-width="3" fill="#BB1E4F" />
                                    </svg>
                                </div>
                                <div class="column">
                                    <span class="actions"></span>
                                </div>
                                <div class="detail">
                                    <p>Consumer key: M5dGYCRA4FEtfccmBH6IGFp8RRddMivK</p>
                                    <p>Consumer secret: 6DxjvPOWlpzTbGe7</p>
                                    <p>Key issued: 02/03/2020 - 21:26</p>
                                    <p>Expires: Never</p>
                                    <p>Callback URL: https://www.plusnarrative.com</p>
                                    <p>Description: Test application</p>

                                    <p>Apps</p>

                                    <div class="app">
                                        <svg height="25" width="25">
                                            <circle cx="12.5" cy="12.5" r="10" stroke="#BB1E4F" stroke-width="3" fill="#BB1E4F" />
                                        </svg>
                                        Subscription v1
                                        @svg('arrow-forward', '#000000')
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="app">
            <div>
                <div style="display: flex; align-items: center;">
                    @svg('chevron-down', '#000000')

                    <h1 style="margin-left: 10px">Revoked Apps</h1>
                </div>

                <div class="my-apps">
                    <div class="head">
                        <div class="column">
                            <p>App name</p>
                        </div>

                        <div class="column">
                            <p>Reason</p>
                        </div>

                        <div class="column">
                            <p>Callback URL</p>
                        </div>

                        <div class="column">
                            <p>Date created</p>
                        </div>

                        <div class="column">
                            <p>Status</p>
                        </div>

                        <div class="column">
                            <p>&nbsp;</p>
                        </div>
                    </div>
                    <div class="body">
                        @foreach($revoked_apps as $app)
                            <div class="my-app">
                                <div class="column">
                                    <p class="name">{{ $app['name'] }}</p>
                                </div>
                                <div class="column">
                                    Lorem ipsum dolor sit amet, consetetur.
                                </div>
                                <div class="column">
                                    <a href="">https://www.appdomain.co.za</a>
                                </div>
                                <div class="column">
                                    {{ \Carbon\Carbon::parse()->format('d M') }}
                                </div>
                                <div class="column">
                                    <svg height="25" width="25">
                                        <circle cx="12.5" cy="12.5" r="10" stroke="#BB1E4F" stroke-width="3" fill="#BB1E4F" />
                                    </svg>
                                </div>
                                <div class="column">
                                    <span class="actions"></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection


@push('scripts')
<script>
    var buttons = document.querySelectorAll('.name');

    buttons.forEach(function(button) {
        button.addEventListener('click', handleButtonClick);
    });

    function handleButtonClick(event) {
        var button = event.currentTarget;
        var detail = document.querySelector('.detail');

        if (detail.style.display === 'block') {
            detail.style.display = 'none';
        } else {
            detail.style.display = 'block';
        }
    }
</script>
@endpush
