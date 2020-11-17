@props(['app', 'details', 'type', 'attr', 'countries'])

@php
    $isAdminPage = Request::is('admin/*');
    $credentials = $app['credentials'];
@endphp

@allowonce('card_link')
<link href="{{ mix('/css/components/_app.css') }}" rel="stylesheet"/>
@endallowonce

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
        @if($isAdminPage)
            {{ $details['email'] ?? '' }}
        @else
            @subStr($app['callback_url'], 30)
        @endif
    </div>
    <div class="column">
        @if($isAdminPage)
            {{ date('Y-m-d', strtotime($app['live_at'])) }}
        @else
            {{ date('Y-m-d', strtotime($app['updated_at'])) }}
        @endif
    </div>
    <div class="column">
        <button class="actions"></button>
    </div>
    <div class="detail">
        @if($isAdminPage)
            <div>
                <div class="detail-left">
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Developer name:</strong></div>
                        <div class="detail-item">{{ ($details['first_name'] ?? 'Not registered')  . ' ' . ($details['last_name'] ?? '') }}</div>
                    </div>
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Developer email:</strong></div>
                        <div class="detail-item">{{ $details['email'] ?? '' }}</div>
                    </div>
                </div>
                <div class="detail-right">
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Countries:</strong></div>
                        <div class="detail-item country-flags">
                            @foreach($countries as $key => $country)
                            <span title="{{$country}}">@svg($key, '#000000', 'images/locations')</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-item"><strong>Description:</strong></div>
                        <div class="detail-item">{{ $app['description'] ?: 'No description' }}</div>
                    </div>
                </div>
            </div>
        @else
            <div>
                <div class="detail-left">
                    <div class="detail-row">
                        <div class="detail-item"><strong>Description:</strong></div>
                        <div class="detail-item">{{ $app['description'] ?: 'No description' }}</div>
                    </div>
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Sandbox key</strong></div>
                        <div class="detail-item key">{{ $credentials[0]['consumerKey'] }}</div>
                        <button class="copy" data-reference="{{$app['aid']}}" data-type="consumerKey-sandbox">
                            @svg('copy', '#000000')
                            @svg('loading', '#000000')
                            @svg('clipboard', '#000000')
                        </button>
                    </div>
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Sandbox secret</strong></div>
                        <div class="detail-item key">{{ $credentials[0]['consumerSecret'] }}</div>
                        <button class="copy" data-reference="{{$app['aid']}}" data-type="consumerSecret-sandbox">
                            @svg('copy', '#000000')
                            @svg('loading', '#000000')
                            @svg('clipboard', '#000000')
                        </button>
                    </div>
                    @if(count($credentials) > 1)
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Production key</strong></div>
                        <div class="detail-item key">{{ end($credentials)['consumerKey'] }}</div>
                        <button class="copy" data-reference="{{$app['aid']}}" data-type="consumerKey-production">
                            @svg('copy', '#000000')
                            @svg('loading', '#000000')
                            @svg('clipboard', '#000000')
                        </button>
                    </div>
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Production secret</strong></div>
                        <div class="detail-item key">{{ end($credentials)['consumerSecret'] }}</div>
                        <button class="copy" data-reference="{{$app['aid']}}" data-type="consumerSecret-production">
                            @svg('copy', '#000000')
                            @svg('loading', '#000000')
                            @svg('clipboard', '#000000')
                        </button>
                    </div>
                    @endif
                </div>
                <div class="detail-right">
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Region:</strong></div>
                        <div class="detail-item country-flags">
                            @foreach($countries as $key => $country)
                            <span title="{{$country}}">@svg($key, '#000000', 'images/locations')</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Key issued:</strong></div>
                        <div class="detail-item">{{ date('d M Y H:i:s', substr($credentials[0]['issuedAt'], 0, 10)) }}</div>
                    </div>
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Expires:</strong></div>
                        <div class="detail-item">Never</div>
                    </div>
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Callback url</strong></div>
                        <div class="detail-item">{{ $app['callback_url'] ?: 'No callback url' }}</div>
                    </div>
                </div>
            </div>
        @endif

        <p class="products-title"><strong>Products</strong></p>
        <div class="products">
            <x-apps.products :app="$app" :products="$credentials[0]['apiProducts']" />
        </div>

        @if(count($credentials) > 1)
        <p class="products-title"><strong>Production products</strong></p>
        <div class="products">
            <x-apps.products :app="$app" :products="end($credentials)['apiProducts']" />
        </div>
        @endif

        @if(!$isAdminPage && is_null($app->live_at))
        <form class="go-live cols centre-align" method="POST" action="{{ route('app.go-live', $app->aid) }}">
            @csrf
            <p class="spacer-flex"><strong class="mr-1">Ready to launch?</strong>You're just a few clicks away</p>
            <button class="button dark">GO LIVE @svg('rocket', '#FFF')</button>
        </form>
        @elseif($isAdminPage && is_null($app->products[0]['pivot']['live_at']))
        <form class="go-live cols centre-align" method="POST" action="{{ route('app.approve', $app->aid) }}">
            @csrf
            <p class="spacer-flex"><strong class="mr-1">Ready to approve?</strong>You're just a few clicks away</p>
            <button class="button dark">APPROVE @svg('check-all', '#FFF')</button>
        </form>
        @endif
    </div>
    <nav class="menu">
        @if($isAdminPage)
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
