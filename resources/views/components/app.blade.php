@allowonce('card_link')
<link href="{{ mix('/css/components/_app.css') }}" rel="stylesheet"/>
@endallowonce

@props(['app', 'type', 'attr', 'countries'])

<div class="app" data-name="{{ $app['name'] }}" data-developer="{{ $app['firstName'] ?? '' }}" data-locations="{{ implode(',', $countries->keys()->all()) }}">
    <div class="column">
        <p class="name">
            @svg('app-avatar', '#fff')
            {{ $attr['DisplayName'] }}
        </p>
    </div>
    @if($type === 'approved')
    <div class="column countries">
        @foreach($countries as $key => $country)
            @if($loop->first)
                @svg($key, '#000000', 'images/locations')
            @endif
        @endforeach

        @if($countries->count() > 1)
        + {{ $countries->count() }} more
        @endif
    </div>
    @else
        <div class="column">
            Lorem ipsum dolor sit amet, consetetur.
        </div>
    @endif
    <div class="column">
        {{ $app['callbackUrl'] }}
    </div>
    <div class="column">
        {{ $app['createdAt'] }}
    </div>
    <div class="column">
        <button class="actions"></button>
    </div>
    <div class="detail">
        @if(Request::is('dashboard'))
            <div>
                <div>
                    <p><strong>Developer name:</strong></p>
                    <p><strong>Developer email:</strong></p>
                </div>
                <div>
                    <p id="developer-name">{{ $app['firstName']  ?? '' }}</p>
                    <p id="developer-email">{{ $app['email'] ?? '' }}</p>
                    <input id="developer-key" type="hidden" value="{{ end($app['credentials'])['consumerKey']  }}">
                    <input id="developer-id" type="hidden" value="{{ $app['developerId']  }}">
                </div>
                <div class="dashboard-countries">
                    <p><strong>Countries:</strong></p>
                </div>
                <div>
                    <div>
                        @foreach($countries as $key => $country)
                            @svg($key, '#000000', 'images/locations')
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div>
                <div>
                    <p><strong>Consumer key:</strong> </p>
                    <p><strong>Consumer secret:</strong> </p>
                    <p><strong>Callback URL:</strong> </p>
                </div>
                <div class="consumer">
                    <p class="key">
                        {{ end($app['credentials'])['consumerKey']  }}
                    </p>
                    <p class="key">
                        {{ end($app['credentials'])['consumerSecret'] }}
                    </p>
                    <p>{{ $app['callbackUrl'] }}</p>
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
                    <p><strong>Countries:</strong></p>
                    <p><strong>Key issued:</strong></p>
                    <p><strong>Expires:</strong></p>
                </div>
                <div>
                    <div>
                        @foreach($countries as $key => $country)
                            @svg($key, '#000000', 'images/locations')
                        @endforeach
                    </div>
                    <p>
                        {{ date('d M Y H:i:s', end($app['credentials'])['issuedAt'] / 1000) }}
                    </p>
                    <p>Never</p>
                </div>
            </div>
        @endif

        <p>
            <strong>Description:</strong>
        </p>

        <p class="description">
            {{ $attr['Description'] }}
        </p>

        <p class="products-title"><strong>Products</strong></p>

        <div class="products">
            <x-apps.products :products="end($app['credentials'])['apiProducts']" />
        </div>
    </div>
    <nav class="menu">
        @if(Request::is('dashboard'))
            <button class="product-all" data-method="approve">Approve all</button>
            <button class="product-all" data-method="revoke">Revoke all</button>
            <button class="product-complete">Complete</button>
        @else
            <a href="{{ route('app.edit', $app['name']) }}">Edit</a>
            <form class="delete">
                @method('DELETE')
                @csrf
                <button class="app-delete" data-name="{{ $app['name'] }}">Delete</button>
            </form>
        @endif
    </nav>
    <div class="modal"></div>
</div>
