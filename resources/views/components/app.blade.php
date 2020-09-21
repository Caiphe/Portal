@allowonce('card_link')
<link href="{{ mix('/css/components/_app.css') }}" rel="stylesheet"/>
@endallowonce

@props(['app', 'details', 'type', 'attr', 'countries'])

<div class="app" data-name="{{ $app['name'] }}" data-id="{{ $app['aid'] }}" data-developer="{{ $app['developer']['first_name'] ?? '' }}"
     data-locations="{{ implode(',', array_keys($countries)) }}">
    <div class="column">
        <p class="name">
            @svg('app-avatar', '#fff')
            {{ $app['display_name'] }}
        </p>
    </div>
    @if($type === 'approved')
    <div class="column countries">
        @foreach($countries as $key => $country)
            @if($loop->index > 0)
                @break
            @endif
            <span title="{{$country}}">@svg($key, '#000000', 'images/locations')</span>
        @endforeach

        @if(count($countries) > 1)
        + {{ count($countries) - 1 }} more
        @endif
    </div>
    @else
        <div class="column"></div>
    @endif
    <div class="column">
        @if(Request::is('admin/*'))
            {{ $details['email'] ?? '' }}
        @else
            @subStr($app['callback_url'], 30)
        @endif
    </div>
    <div class="column">
        {{ date('Y-m-d', strtotime($app['updated_at'])) }}
    </div>
    <div class="column">
        <button class="actions"></button>
    </div>
    <div class="detail">
        @if(Request::is('admin/*'))
            <div>
                <div>
                    <p><strong>Developer name:</strong> </p>
                    <p><strong>Developer email:</strong> </p>
                </div>
                <div>
                    <p id="developer-name">{{ ($details['first_name'] ?? 'Not registered')  . ' ' . ($details['last_name'] ?? '') }}</p>
                    <p id="developer-email">{{ $details['email'] ?? '' }}</p>
                </div>
                <div class="copy-column"><!--This is a placeholder--></div>
                <div>
                    <p><strong>Countries:</strong></p>
                </div>
                <div>
                    <div class="country-flags">
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
                    <p class="key">{{ $app['credentials']['consumerKey'] }}</p>
                    <p class="key">{{ $app['credentials']['consumerSecret'] }}</p>
                    <p>{{ $app['callback_url'] }}</p>
                </div>
                <div class="copy-column">
                    <button class="copy" data-reference="{{$app['aid']}}" data-type="consumerKey">
                        @svg('copy', '#000000')
                        @svg('loading', '#000000')
                        @svg('clipboard', '#000000')
                    </button>
                    <button class="copy" data-reference="{{$app['aid']}}" data-type="consumerSecret">
                        @svg('copy', '#000000')
                        @svg('loading', '#000000')
                        @svg('clipboard', '#000000')
                    </button>
                </div>
                <div>
                    <p><strong>Key issued:</strong></p>
                    <p><strong>Expires:</strong></p>
                    <p><strong>Countries:</strong></p>
                </div>
                <div>
                    <p>
                        {{ date('d M Y H:i:s', substr($app['credentials']['issuedAt'], 0, 10)) }}
                    </p>
                    <p>Never</p>
                    <div class="country-flags">
                        @foreach($countries as $key => $country)
                            <span title="{{$country}}">@svg($key, '#000000', 'images/locations')</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <p>
            <strong>Description:</strong>
        </p>

        <p class="description">
            {{ $app['description'] }}
        </p>

        <p class="products-title"><strong>Products</strong></p>

        <div class="products">
            <x-apps.products :app="$app" />
        </div>
    </div>
    <nav class="menu">
        @if(Request::is('admin/*'))
            @can('administer-dashboard')
            <button class="product-all" data-action="approve">Approve all</button>
            <button class="product-all" data-action="revoke">Revoke all</button>
            @else
            <button>View only</button>
            @endcan
        @else
            <a href="{{ route('app.edit', $app['slug']) }}">Edit</a>
            <form class="delete">
                @method('DELETE')
                @csrf
                <button class="app-delete" data-name="{{ $app['name'] }}">Delete</button>
            </form>
        @endif
    </nav>
    <div class="modal"></div>
</div>
