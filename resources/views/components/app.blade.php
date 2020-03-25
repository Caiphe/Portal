@allowonce('card_link')
<link href="/css/components/app.css" rel="stylesheet"/>
@endallowonce

{{--@props(['icon'])--}}

<div class="my-app" {{ $attributes }}>
    <div class="column">
        <p class="name">
            @svg('app-avatar', '#fff')
            {{ $name }}
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

