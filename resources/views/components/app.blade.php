@allowonce('card_link')
<link href="/css/components/app.css" rel="stylesheet"/>
@endallowonce

@props(['app', 'attr'])

{{--{{ dd($app) }}--}}

<div class="my-app">
    <div class="column">
        <p class="name">
            @svg('app-avatar', '#fff')
            {{ $attr['DisplayName'] }}
        </p>
    </div>
    <div class="column regions">
        @svg('za', '#000000', 'images/locations')
        + 2 more
    </div>
    <div class="column">
        https://www.appdomain.co.za
    </div>
    <div class="column">
        {{ \Carbon\Carbon::parse()->format('d M') }}
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
                <p class="key">
                    {{ end($app['credentials'])['consumerKey']  }}
                </p>
                <p class="key">
                    {{ end($app['credentials'])['consumerSecret'] }}
                </p>
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
                <p>
                    {{ \Carbon\Carbon::parse(end($app['credentials'])['issuedAt'])->format('d/m/y - H:i') }}
                </p>
                <p>Never</p>
            </div>
        </div>

        <p>
            <strong>Description:</strong>
        </p>

        <p class="description">
            {{ $attr['Description'] }}
        </p>

        <p class="description"><strong>Products</strong></p>

        <div class="products">
            @foreach(end($app['credentials'])['apiProducts'] as $product)
                <div class="product">
                    <span class="status-{{ $product['status'] }}"></span>
                    <span class="name">{{ $product['apiproduct'] }}</span>
                    @svg('arrow-forward', '#000000')
                </div>
            @endforeach
        </div>
    </div>
    <nav class="menu">
        <a href="">Edit</a>
        <a href="">Delete</a>
    </nav>
    <div class="modal"></div>
</div>

