@props(['app', 'appStagingProducts', 'details', 'type', 'attr', 'countries'])

@php
    $isAdminPage = Request::is('admin/*');
    $credentials = $app['credentials'];
    [$sandboxProducts, $prodProducts] = $app->getProductsByCredentials();
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
            {{ date('Y-m-d', strtotime($app['live_at'] ?? $app['updated_at'])) }}
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
                    <div class="detail-row">
                        <div class="detail-item"><strong>Description:</strong></div>
                        <div class="detail-item">{{ $app['description'] ?: 'No description' }}</div>
                    </div>
                </div>
            </div>
        @elseif(!empty($sandboxProducts['products']))
            <div>
                <div class="detail-left">
                    <div class="detail-row">
                        <div class="detail-item"><strong>Description:</strong></div>
                        <div class="detail-item">{{ $app['description'] ?: 'No description' }}</div>
                    </div>
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Sandbox key</strong></div>
                        <div class="detail-item key">{{ $sandboxProducts['credentials']['consumerKey'] }}</div>
                        <button class="copy" data-reference="{{$app['aid']}}" data-type="consumerKey-sandbox">
                            @svg('copy', '#000000')
                            @svg('loading', '#000000')
                            @svg('clipboard', '#000000')
                        </button>
                    </div>
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Sandbox secret</strong></div>
                        <div class="detail-item key">{{ $sandboxProducts['credentials']['consumerSecret'] }}</div>
                        <button class="copy" data-reference="{{$app['aid']}}" data-type="consumerSecret-sandbox">
                            @svg('copy', '#000000')
                            @svg('loading', '#000000')
                            @svg('clipboard', '#000000')
                        </button>
                    </div>
                </div>
                <div class="detail-right">
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Callback url</strong></div>
                        <div class="detail-item">{{ $app['callback_url'] ?: 'No callback url' }}</div>
                    </div>
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Expires:</strong></div>
                        <div class="detail-item">Never</div>
                    </div>
                    <div class="detail-row cols">
                        <div class="detail-item"><strong>Key issued:</strong></div>
                        <div class="detail-item">{{ date('d M Y H:i:s', substr($sandboxProducts['credentials']['issuedAt'], 0, 10)) }}</div>
                    </div>
                </div>
            </div>
        @endif

        @if(!empty($sandboxProducts))
        <p class="products-title"><strong>Products</strong></p>
        <div class="products">
            <x-apps.products :app="$app" :products="$sandboxProducts['products']" for="staging" />
        </div>
        @endif

        @if(!empty($sandboxProducts) && $isAdminPage)
            <div class="detail-right status-change-note">
                <div class="detail-row">
                    <div class="detail-item"><strong>Add note:</strong></div>
                    <textarea id="status-change-note" name="additional_status_change_note" rows="4" cols="50" placeholder="Optional product status change note"></textarea>
                </div>
            </div>
        @endif
        @if(!empty($prodProducts['products']))
        @if(!$isAdminPage)
        <div class="mt-2">
            <div class="detail-left">
                @if(empty($sandboxProducts))
                <div class="detail-row">
                    <div class="detail-item"><strong>Description:</strong></div>
                    <div class="detail-item">{{ $app['description'] ?: 'No description' }}</div>
                </div>
                @endif
                <div class="detail-row cols">
                    <div class="detail-item"><strong>Production key</strong></div>
                    <div class="detail-item key">{{ $prodProducts['credentials']['consumerKey'] }}</div>
                    <button class="copy" data-reference="{{$app['aid']}}" data-type="consumerKey-production">
                        @svg('copy', '#000000')
                        @svg('loading', '#000000')
                        @svg('clipboard', '#000000')
                    </button>
                </div>
                <div class="detail-row cols">
                    <div class="detail-item"><strong>Production secret</strong></div>
                    <div class="detail-item key">{{ $prodProducts['credentials']['consumerSecret'] }}</div>
                    <button class="copy" data-reference="{{$app['aid']}}" data-type="consumerSecret-production">
                        @svg('copy', '#000000')
                        @svg('loading', '#000000')
                        @svg('clipboard', '#000000')
                    </button>
                </div>
                @if(!is_null($app['kyc_status']))
                <div class="detail-row cols">
                    <div class="detail-item"><strong>KYC status</strong></div>
                    <div class="detail-item">{{ $app['kyc_status'] }}</div>
                </div>
                @endif
            </div>
            <div class="detail-right">
                @if(empty($sandboxProducts))
                <div class="detail-row cols">
                    <div class="detail-item"><strong>Callback url</strong></div>
                    <div class="detail-item">{{ $app['callback_url'] ?: 'No callback url' }}</div>
                </div>
                @endif
                <div class="detail-row cols">
                    <div class="detail-item"><strong>Expires:</strong></div>
                    <div class="detail-item">Never</div>
                </div>
                <div class="detail-row cols">
                    <div class="detail-item"><strong>Key issued:</strong></div>
                    <div class="detail-item">{{ date('d M Y H:i:s', substr(end($credentials)['issuedAt'], 0, 10)) }}</div>
                </div>
            </div>
        </div>
        @endif
        <div class="detail-right"></div>
        <p class="products-title"><strong>Production products</strong></p>
        <div class="products production-products kyc-status-{{ Str::slug($app->kyc_status ?? 'none') }}">
            <x-apps.products :app="$app" :products="$prodProducts['products']" for="production" />
        </div>
        @endif

        @if(!empty($sandboxProducts) && $isAdminPage)
            <div class="detail-right status-change-note">
                <div class="detail-row">
                    <div class="detail-item"><strong>Add note:</strong></div>
                    <textarea id="status-change-note" name="additional_status_change_note" rows="4" cols="50" placeholder="Optional product status change note"></textarea>
                </div>
            </div>
        @endif

        @if(!$isAdminPage && !empty($sandboxProducts) && is_null($app->live_at))
        <form class="go-live cols centre-align" method="POST" action="{{ route('app.go-live', $app->aid) }}">
            @csrf
            <p class="spacer-flex"><strong class="mr-1">Ready to launch?</strong>You're just a few clicks away</p>
            <button class="button dark">GO LIVE @svg('rocket', '#FFF')</button>
        </form>
        @elseif($isAdminPage && $prodProducts['hasKyc'])
        <div class="kyc-status">
            <strong class="mr-2">Update the KYC status</strong>
            <select name="kyc_status" class="kyc-status-select" data-aid="{{ $app['aid'] }}" autocomplete="off">
                <option @if($app['kyc_status'] === 'Documents Received') selected @endif value="Documents Received">Documents Received</option>
                <option @if($app['kyc_status'] === 'In Review') selected @endif value="In Review">In Review</option>
                <option @if($app['kyc_status'] === 'KYC Approved') selected @endif value="KYC Approved">KYC Approved</option>
            </select>
        </div>
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
