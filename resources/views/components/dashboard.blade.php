@props(['app', 'appStagingProducts', 'details', 'type', 'attr', 'countries'])

@php
    $credentials = $app['credentials'];
    [$sandboxProducts, $prodProducts] = $app->getProductsByCredentials();
    $hasSandboxProducts = !empty($sandboxProducts);
    $hasProdProducts = !empty($prodProducts) && !empty($prodProducts['products']);
    $productStatus = $app->product_status;
    $countryCode = array_keys($countries)[0];
    $countryName = array_values($countries)[0];
@endphp

<div class="app app-status-{{ $productStatus['status'] }} @if(request()->has('aid')) show  @endif" data-status="{{ $app['status'] }}" data-id="{{ $app['aid'] }}"  id="wrapper-{{ $app['aid'] }}">
    <div class="columns">
        <div class="column column-app-name">
            <p class="name elipsise toggle-app">
                {{ $app['display_name'] }}
            </p>
        </div>

        <div class="column column-country">
            <p title="{{ $countryName }}">@svg($countryCode, '#000000', 'images/locations')</p>
        </div>

        <div class="column column-developer-company">
            <p class="elipsise">{{ isset($details['developer_id']) ? $details['email'] : $details['name'] ?? '' }}</p>
        </div>

        <div class="column column-go-live">
            {{ date('d M Y', strtotime($app['live_at'] ?? $app['updated_at'])) }}
        </div>

        <div class="column column-status">
            <span class="app-status" aria-label="{{ $productStatus['label'] }}" data-pending="{{ $productStatus['pending'] }}"></span>
            <button class="toggle-app-button toggle-app reset">@svg('chevron-down', '#000000')</button>
        </div>
    </div>

    <div @class([
        'detail',
        'active-production' => $hasProdProducts,
        'active-sandbox' => !$hasProdProducts,
    ])>
        <h2>Product details</h2>
        <div class="environments">
            @if($hasProdProducts)
            <button class="reset environment environment-production" data-environment="production">Production</button>
            @endif
            @if($hasSandboxProducts)
            <button class="reset environment environment-sandbox" data-environment="sandbox">Sandbox</button>
            @endif
        </div>

        @if($hasProdProducts)
        <div class="app-products production-products active">
            <form class="renew-credentials" action="{{ route('admin.credentials.renew', ['app' => $app, 'type' => 'production']) }}" method="POST">
                @csrf
                <button class="reset renew">@svg('renew') Renew production credentials</button>
            </form>

            <x-admin.dashboard.products :app="$app" :products="$prodProducts['products']" for="production" />
        </div>
        @endif

        @if($hasSandboxProducts)
        <div class="app-products sandbox-products">
            <form class="renew-credentials" action="{{ route('admin.credentials.renew', ['app' => $app, 'type' => 'sandbox']) }}" method="POST">
                @csrf
                <button class="reset renew">@svg('renew') Renew sandbox credentials</button>
            </form>

            <x-admin.dashboard.products :app="$app" :products="$sandboxProducts['products']" for="staging" />
        </div>
        @endif

        <div class="detail-items">
            <div class="detail-left">
                <h3>Application details</h3>
                <p>Callback URL: @if($app['callback_url']) <a href="{{ $app['callback_url'] }}" target="_blank" rel="noopener noreferrer">{{ $app['callback_url'] }}</a> @else <span class="detail-text"> No callback url</span> @endif</p>
                <p>Description: <span class="detail-text">{{ $app['description'] ?: 'No description' }}</span></p>
                @if(!is_null($app['kyc_status']))
                <p>
                    Update the KYC status:
                    <select name="kyc_status" class="kyc-status-select" data-aid="{{ $app['aid'] }}" autocomplete="off">
                        <option @if($app['kyc_status'] === 'Documents Received') selected @endif value="Documents Received">Documents received</option>
                        <option @if($app['kyc_status'] === 'In Review') selected @endif value="In Review">In review</option>
                        <option @if($app['kyc_status'] === 'KYC Approved') selected @endif value="KYC Approved">KYC approved</option>
                    </select>
                </p>
                @endif
                <div class="detail-actions">
                    @if($app['status'] === 'approved')
                    <button class="reset app-status-action" data-id="{{ $app['aid'] }}" data-status="revoked" data-action="{{ route('admin.app.status-update', $app) }}">@svg('revoke') Revoke application</button>
                    @else
                    <button class="reset app-status-action" data-id="{{ $app['aid'] }}" data-status="approved" data-action="{{ route('admin.app.status-update', $app) }}">@svg('approve') Approve application</button>
                    @endif
                    <button class="log-notes reset" data-id="{{ $app['aid'] }}">@svg('view') View application log notes</button>
                </div>
            </div>
            <div class="detail-right">
                <h3>Developer details</h3>
                @if(isset($details['developer_id']))
                <p>Name: <a href="{{ route('admin.user.edit', $details) }}" target="_blank" rel="noopener noreferrer">{{ $details->full_name ?? 'User not in portal' }}</a></p>
                @else
                <p>Name: <span class="detail-text">{{ $details->full_name ?? 'User not in portal' }}</span></p>
                @endif
                <p>Email address: @if(isset($details->email))<a href="mailto:{{ $details->email }}">{{ $details->email }}</a>@endif</p>
            </div>
        </div>
    </div>
</div>
