@allowonce('card_link')
<link href="{{ mix('/css/components/_app.css') }}" rel="stylesheet"/>
@endallowonce

@props(['app', 'details', 'type', 'attr', 'countries'])

<div class="app" data-name="{{ $app['name'] }}" data-id="{{ $app['appId'] }}" data-developer="{{ $app['firstName'] ?? '' }}"
     data-locations="{{ implode(',', $countries->keys()->all()) }}">
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
                <span title="{{$country}}">@svg($key, '#000000', 'images/locations')</span>
            @endif
        @endforeach

        @if($countries->count() > 1)
        + {{ $countries->count() - 1 }} more
        @endif
    </div>
    @else
        <div class="column">
            Lorem ipsum dolor sit amet, consetetur.
        </div>
    @endif
    <div class="column">
        @if(Request::is('dashboard'))
            {{ $app['email'] ?? '' }}
        @else
            {{ $app['callbackUrl'] }}
        @endif
    </div>
    <div class="column">
        {{ date('d M Y', substr($app['createdAt'], 0, 10)) }}
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
                    <p id="developer-name">{{ $details['firstName']  . ' ' . $details['lastName'] }}</p>
                    <p id="developer-email">{{ $details['email'] ?? '' }}</p>
                    <input id="developer-key" type="hidden" value="{{ $app['credentials']['consumerKey']  }}">
                    <input id="developer-id" type="hidden" value="{{ $app['developerId']  }}">
                </div>
                <div class="dashboard-countries">
                    <p><strong>Countries:</strong></p>
                </div>
                <div>
                    <div>
                        @foreach($countries as $key => $country)
                            <span title="{{$country}}">@svg($key, '#000000', 'images/locations')</span>
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
                        <input type="text" name="consumerKey" id="{{$app['appId']}}-consumer-key" value="{{ $app['credentials']['consumerKey']  }}" readonly>
                    </p>
                    <p class="key">
                        <input type="text" name="consumerSecret" id="{{$app['appId']}}-consumer-secret" value="{{ $app['credentials']['consumerSecret']  }}" readonly>
                    </p>
                    <p>{{ $app['callbackUrl'] }}</p>
                </div>
                <div class="copy-column">
                    <button class="copy" data-reference="{{$app['appId']}}-consumer-key">
                        @svg('copy', '#000000')
                        @svg('clipboard', '#000000')
                    </button>
                    <button class="copy" data-reference="{{$app['appId']}}-consumer-secret">
                        @svg('copy', '#000000')
                        @svg('clipboard', '#000000')
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
                        {{ date('d M Y H:i:s', substr($app['credentials']['issuedAt'], 0, 10)) }}
                    </p>
                    <p>Never</p>
                </div>
            </div>
        @endif

        <p>
            <strong>Description:</strong>
        </p>

        <p class="description">
            {{ $attr['Description'] ?? '' }}
        </p>

        <p class="products-title"><strong>Products</strong></p>

        <div class="products">
            <x-apps.products :products="$app['credentials']['apiProducts']" />
        </div>
    </div>
    <nav class="menu">
        @if(Request::is('dashboard'))
            <button class="product-all" data-action="approve">Approve all</button>
            <button class="product-all" data-action="revoke">Revoke all</button>
            <button class="complete">Complete</button>
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
