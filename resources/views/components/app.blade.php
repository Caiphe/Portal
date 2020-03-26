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
        + 2 more
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
        <button class="actions"></button>
    </div>
    <div class="detail">
        <div>
            <div>
                <p><strong>Consumer key:</strong> </p>
                <p><strong>Consumer secret:</strong> </p>
                <p><strong>Callback URL:</strong> </p>
            </div>
            <div>
                <p class="key">M5dGYCRA4FEtfccmBH6IGFp8RRddMivK</p>
                <p class="key">6DxjvPOWlpzTbGe7</p>
                <p>https://www.plusnarrative.com</p>
            </div>
            <div class="copy-column">
                <button class="copy">
                    @svg('copy', '#000000')
                </button>
                <button class="copy">
                    @svg('copy', '#000000')
                </button>
            </div>
            <div>
                <p><strong>Regions:</strong></p>
                <p><strong>Key issued:</strong></p>
                <p><strong>Expires:</strong></p>
            </div>
            <div>
                <div>
                    @svg('za', '#000000', 'images/locations')
                </div>
                <p>02/03/2020 - 21:26</p>
                <p>Never</p>
            </div>
        </div>

        <p>
            <strong>Description:</strong>
        </p>

        <p style="margin-bottom: 10px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis ipsa magnam perspiciatis quaerat rem! Asperiores corporis delectus error esse et, excepturi in nemo nulla odit officia officiis quo ullam, unde!</p>

        <p style="margin-bottom: 10px;"><strong>Products</strong></p>

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

            <div class="product">
                <svg height="25" width="25">
                    <circle cx="12.5" cy="12.5" r="10" stroke="#BB1E4F" stroke-width="3" fill="#BB1E4F" />
                </svg>
                <span>Product name which is really super dooper extra long</span>
                @svg('arrow-forward', '#000000')
            </div>
        </div>
    </div>
    <nav class="menu">
        <a href="">Analytics</a>
        <a href="">Edit</a>
        <a href="">Delete</a>
    </nav>
</div>

