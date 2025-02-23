@props(['app', 'appStagingProducts', 'details', 'type', 'attr', 'countries'])

@php
    $user = auth()->user();
    $isAdminPage = Request::is('admin/*');
    $credentials = $app['credentials'];
    [$sandboxProducts, $prodProducts] = $app->getProductsByCredentials();
    $appStatus = $app->products->filter(fn($prod) => $prod->pivot->status === 'pending')->count() > 0 ? 'pending' : $app['status'];
    $countryCode = array_keys($countries)[0];
    $countryName = array_values($countries)[0];

    $team = null;
    if (isset($app['team_id'])) {
        $team = \App\Team::find($app['team_id']);
    }
@endphp

@once
@push('styles')
<link href="{{ mix('/css/components/_app.css') }}" rel="stylesheet"/>
@endpush
@endonce

<div class="app app-status-{{ $appStatus }}" data-name="{{ $app['name'] }}" data-id="{{ $app['aid'] }}" data-developer="{{ $app['developer']['first_name'] ?? '' }}"
     data-locations="{{ $countryCode }}">

     <div class="column">
        <p class="name toggle-app">
            <span title="{{ $appStatus }}" class="status-icon"></span>
            <span class="app-name">  {{ $app['display_name'] }}</span>
        </p>
    </div>

    <div class="column">
        <a href="#" target="_blank" class="bold">{{ $app['callback_url'] }}</a>
    </div>

    @if($type === 'approved')
    <div class="column countries">
        <p title="{{ $countryName }}">@svg($countryCode, '#000000', 'images/locations')</p>
    </div>
    @else
        <div class="column"></div>
    @endif

    {{-- Creatorn column--}}
    <div class="column flexed-column">
        <div class="creator-thumbail" style="background-image: url({{ is_null($team) ? $user->profile_picture : $team->logo }})"></div>
        <div class="creator-name">{{ is_null($team) ? $user->full_name : $team->name }}</div>
    </div>

    <div class="column">
       {{ $app->created_at->format('d M Y') }}
    </div>

    <div class="column">
        <button class="actions"></button>
        <button class="toggle-app-button toggle-app">@svg('chevron-down', '#000000')</button>
    </div>

    <div class="detail">
        @if($isAdminPage)
        <div class="mt-2">

            <div class="detail-left">

                <div class="detail-row cols no-wrap">
                    <div class="detail-item"><strong>Production key</strong></div>
                    <div class="detail-item key">{{ $prodProducts['credentials']['consumerKey'] }}</div>
                    <button class="copy" data-reference="{{$app['aid']}}" data-type="consumerKey-production">
                        @svg('copy', '#000000')
                        @svg('loading', '#000000')
                        @svg('clipboard', '#000000')
                    </button>
                </div>

                <div class="detail-row cols no-wrap">
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

                {{-- Country name to be updated --}}
                @if(empty($sandboxProducts))
                <div class="detail-row cols">
                    <div class="detail-item"><strong>Country</strong></div>
                    <div class="detail-item">{{ $countryName }}</div>
                </div>
                @endif

                @if(empty($sandboxProducts))
                <div class="detail-row">
                    <div class="detail-item"><strong>Description:</strong></div>
                    <div class="detail-item detail-item-description">{{ $app['description'] ?: 'No description' }}</div>
                </div>
                @endif
            </div>

            <div class="detail-right">

                <div class="detail-row cols">
                    <div class="detail-item"><strong>Key issued:</strong></div>
                    <div class="detail-item">{{ date('d M Y H:i:s', substr(end($credentials)['issuedAt'], 0, 10)) }}</div>
                </div>

                <div class="detail-row cols">
                    <div class="detail-item"><strong>Expires:</strong></div>
                    <div class="detail-item">Never</div>
                </div>

            </div>
        </div>
        @endif

        @if(!empty($sandboxProducts))
        <div class="mt-2 sandbox-container">

            <div class="detail-left">
               
                <div class="detail-row cols no-wrap">
                    <div class="detail-item"><strong>Sandbox key</strong></div>
                    <div class="detail-item key">{{ $sandboxProducts['credentials']['consumerKey'] }}</div>
                    <button class="copy copy-btn" data-reference="{{$app['aid']}}" data-type="consumerKey-sandbox">
                        @svg('copy', '#000000')
                        @svg('loading', '#000000')
                        @svg('clipboard', '#000000')
                    </button>
                </div>

                <div class="detail-row cols no-wrap">
                    <div class="detail-item"><strong>Sandbox secret</strong></div>
                    <div class="detail-item key">{{ $sandboxProducts['credentials']['consumerSecret'] }}</div>
                    <button class="copy copy-btn" data-reference="{{$app['aid']}}" data-type="consumerSecret-sandbox">
                        @svg('copy', '#000000')
                        @svg('loading', '#000000')
                        @svg('clipboard', '#000000')
                    </button>
                </div>

                <div class="detail-row details-row-container">
                    <div class="detail-item"><strong>Description:</strong></div>
                    <div class="detail-item detail-item-description">{{ $app['description'] ?: 'No description' }}</div>
                </div>
            </div>
            <div class="detail-right">
               
                <div class="detail-row cols">
                    <div class="detail-item"><strong>Key issued:</strong></div>
                    <div class="detail-item">{{ date('d M Y H:i:s', substr($sandboxProducts['credentials']['issuedAt'], 0, 10)) }}</div>
                </div>

                <div class="detail-row cols">
                    <div class="detail-item"><strong>Expires:</strong></div>
                    <div class="detail-item">Never</div>
                </div>
            </div>
        </div>


        @if(!$isAdminPage)
        <div class="products-title">
            <strong>Products</strong>
            <form class="ml-1" action="{{ route('app.credentials.request-renew', ['app' => $app, 'type' => 'sandbox']) }}" method="POST" onsubmit="if(confirm('Renewing the credentials will revoke the current ones, do you want to continue?')){addLoading('Renewing credentials...')}else{return false};">
                @csrf
                <button class="outline small">Renew credentials</button>
            </form>
        </div>
        @else
        <div class="products-title">
            <strong>Products</strong>
            <form class="ml-1" action="{{ route('admin.credentials.renew', ['app' => $app, 'type' => 'sandbox']) }}" method="POST" onsubmit="if(confirm('Renewing the credentials will revoke the current ones, do you want to continue?')){addLoading('Renewing credentials...')}else{return false};">
                @csrf
                <button class="outline small">Renew credentials</button>
            </form>
        </div>
        @endif

        <div class="products">
            <x-apps.products :app="$app" :products="$sandboxProducts['products']" for="staging" />
        </div>
        @endif

        @if(!empty($prodProducts['products']))
        @if(!$isAdminPage)
        <div class="mt-2">
            <div class="detail-left">

                <div class="detail-row cols no-wrap">
                    <div class="detail-item"><strong>Consumer key:</strong></div>
                    <div class="detail-item key">{{ $prodProducts['credentials']['consumerKey'] }}</div>
                    <button class="copy copy-btn" data-reference="{{$app['aid']}}" data-type="consumerKey-production">
                        @svg('copy', '#000000')
                        @svg('loading', '#000000')
                        @svg('clipboard', '#000000')
                    </button>
                </div>

                <div class="detail-row cols no-wrap">
                    <div class="detail-item"><strong>Consumer secret:</strong></div>
                    <div class="detail-item key">{{ $prodProducts['credentials']['consumerSecret'] }}</div>
                    <button class="copy copy-btn" data-reference="{{$app['aid']}}" data-type="consumerSecret-production">
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

                @if(empty($sandboxProducts))
                <div class="detail-row cols">
                    <div class="detail-item"><strong>Country:</strong></div>
                    <div class="detail-item">{{ $countryName }}</div>
                </div>
                @endif

                @if(empty($sandboxProducts))
                <div class="detail-row details-row-container">
                    <div class="detail-item"><strong>Description:</strong></div>
                    <div class="detail-item detail-item-description">
                        {{ $app['description'] ?: 'No description' }}
                    </div>
                </div>
                @endif

                @if($app['entity_name'])
                <div class="detail-row details-row-container">
                    <div class="detail-item"><strong>Entity Name:</strong></div>
                    <div class="detail-item detail-item-description">{{ $app['entity_name'] }}</div>
                </div>
                @endif

            </div>
            <div class="detail-right">

                <div class="detail-row cols">
                    <div class="detail-item"><strong>Key issued:</strong></div>
                    <div class="detail-item">{{ date('d M Y H:i:s', substr(end($credentials)['issuedAt'], 0, 10)) }}</div>
                </div>

                <div class="detail-row cols">
                    <div class="detail-item"><strong>Expires:</strong></div>
                    <div class="detail-item">Never</div>
                </div>

                <div class="detail-row cols">
                    <div class="detail-item"><strong>Contact Number:</strong></div>
                    <div class="detail-item">{{ $app['contact_number'] }}</div>
                </div>
                
                <div class="detail-row cols">
                    <div class="detail-item"><strong>Channels:</strong></div>
                    <div class="detail-item">{{ $app['channels'] }}</div>
                </div>

            </div>
        </div>
        @endif
        <div class="detail-right"></div>


        @if(!$isAdminPage)
        <div class="products-title">
            <strong>Production products</strong>
            <form class="ml-1" action="{{ route('app.credentials.request-renew', ['app' => $app, 'type' => 'production']) }}" method="POST" onsubmit="if(confirm('Renewing the credentials will revoke the current ones, do you want to continue?')){addLoading('Renewing credentials...')}else{return false};">
                @csrf
                {{--- Please do not remove this button  --}}
                <button class="outline small" href="">Renew credentials</button>
            </form>
        </div>
        @else
        <div class="products-title">
            <strong>Production products</strong>
            <form class="ml-1" action="{{ route('admin.credentials.renew', ['app' => $app, 'type' => 'production']) }}" method="POST" onsubmit="if(confirm('Renewing the credentials will revoke the current ones, do you want to continue?')){addLoading('Renewing credentials...')}else{return false};">
                @csrf
                {{--- Please do not remove this button  --}}
                <button class="outline small" href="">Renew credentials</button>
            </form>
        </div>
        @endif

        <div class="products production-products kyc-status-{{ Str::slug($app->kyc_status ?? 'none') }}">
            <x-apps.products :app="$app" :products="$prodProducts['products']" for="production" />
        </div>
        @endif

        @if(!$isAdminPage && !empty($sandboxProducts) && is_null($app->live_at))
        <form class="go-live cols centre-align" method="POST" action="{{ route('app.go-live', $app->aid) }}">
            @csrf
            <p class="spacer-flex"><strong class="mr-1">Ready to launch?</strong>You're just a few clicks away</p>
            <button class="button dark">GO LIVE @svg('rocket', '#FFF')</button>
        </form>
        @elseif($isAdminPage && !is_null($app['kyc_status']))
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
            @if($app['status'] === 'revoked')
            <button class="app-status-update" data-status="approved" data-action="{{ route('admin.app.status-update', $app['aid']) }}">Approve Application</button>
            @elseif($app['status'] === 'approved')
            <button class="app-status-update" data-status="revoked" data-action="{{ route('admin.app.status-update', $app['aid']) }}">Revoke Application</button>
            @endif
            <div class="status-separator"></div>
            <button class="product-all" data-action="approve">Approve all products</button>
            <button class="product-all" data-action="revoke">Revoke all products</button>
            @else
            <button>View only</button>
            @endcan
        @else
            <a href="{{ route('app.edit', $app['slug']) }}">Edit</a>
            <button class="app-delete-modal" 
                data-appname="{{ $app['slug'] }}"
                data-displayname="{{ $app['display_name'] }}">Delete
            </button>
        @endif
    </nav>
    <div class="modal"></div>
</div>
