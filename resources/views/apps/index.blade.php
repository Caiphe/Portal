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

                    <h3 style="margin-left: 10px">Approved Apps</h3>
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
                                    <p class="name">
                                        @svg('app-avatar', '#fff')
                                        {{ $app['name'] }}
                                    </p>
                                </div>
                                <div class="column regions">
                                    @svg('za', '#000000', 'images/locations')
                                    @svg('af', '#000000', 'images/locations')
                                    @svg('bf', '#000000', 'images/locations')
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
{{--                                    <nav class="menu">--}}
{{--                                        <a>Analytics</a>--}}
{{--                                        <a>Edit</a>--}}
{{--                                        <a>Delete</a>--}}
{{--                                    </nav>--}}
                                </div>
                                <div class="detail">
                                    <p>Consumer key: M5dGYCRA4FEtfccmBH6IGFp8RRddMivK</p>
                                    <p>Consumer secret: 6DxjvPOWlpzTbGe7</p>
                                    <p>Key issued: 02/03/2020 - 21:26</p>
                                    <p>Expires: Never</p>
                                    <p>Callback URL: https://www.plusnarrative.com</p>
                                    <p>Description: Test application</p>

                                    <p>Products</p>

                                    <div class="products">
                                        <div class="product">
                                            <svg height="25" width="25">
                                                <circle cx="12.5" cy="12.5" r="10" stroke="#BB1E4F" stroke-width="3" fill="#BB1E4F" />
                                            </svg>
                                            <span>Subscription v1</span>
                                            @svg('arrow-forward', '#000000')
                                        </div>

                                        <div class="product">
                                            <svg height="25" width="25">
                                                <circle cx="12.5" cy="12.5" r="10" stroke="#BB1E4F" stroke-width="3" fill="#BB1E4F" />
                                            </svg>
                                            <span>SMS</span>
                                            @svg('arrow-forward', '#000000')
                                        </div>
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

                    <h3 style="margin-left: 10px">Revoked Apps</h3>
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
                                    <p class="name">
                                        @svg('app-avatar', '#fff')
                                        {{ $app['name'] }}
                                    </p>
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
{{--                                    <nav class="menu">--}}
{{--                                        <a>Analytics</a>--}}
{{--                                        <a>Edit</a>--}}
{{--                                        <a>Delete</a>--}}
{{--                                    </nav>--}}
                                </div>
                                <div class="detail">
                                    <p>Consumer key: M5dGYCRA4FEtfccmBH6IGFp8RRddMivK</p>
                                    <p>Consumer secret: 6DxjvPOWlpzTbGe7</p>
                                    <p>Key issued: 02/03/2020 - 21:26</p>
                                    <p>Expires: Never</p>
                                    <p>Callback URL: https://www.plusnarrative.com</p>
                                    <p>Description: Test application</p>

                                    <p>Products</p>

                                    <div class="products">
                                        <div class="product">
                                            <svg height="25" width="25">
                                                <circle cx="12.5" cy="12.5" r="10" stroke="#BB1E4F" stroke-width="3" fill="#BB1E4F" />
                                            </svg>
                                            <span>Subscription v1</span>
                                            @svg('arrow-forward', '#000000')
                                        </div>

                                        <div class="product">
                                            <svg height="25" width="25">
                                                <circle cx="12.5" cy="12.5" r="10" stroke="#BB1E4F" stroke-width="3" fill="#BB1E4F" />
                                            </svg>
                                            <span>SMS</span>
                                            @svg('arrow-forward', '#000000')
                                        </div>
                                    </div>
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

    for (var i = 0; i < buttons.length; i ++) {
        buttons[i].addEventListener('click', handleButtonClick);
    }

    function handleButtonClick(event) {
        var button = event.currentTarget;
        var detail = document.querySelector('.detail');

        if (detail.style.display === 'block') {
            detail.style.display = 'none';
        } else {
            detail.style.display = 'block';
        }
    }

    var actions = document.querySelectorAll('.actions');

    for (var i = 0; i < actions.length; i ++) {
        actions[i].addEventListener('click', handleMenuClick);
    }

    function handleMenuClick() {
        var menu = document.querySelector('.menu');

        menu.style.display = 'block';
    }
</script>
@endpush
