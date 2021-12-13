@props(['app', 'appStagingProducts', 'details', 'type', 'attr', 'countries'])

@php
    $credentials = $app['credentials'];
    [$sandboxProducts, $prodProducts] = $app->getProductsByCredentials();
    $productStatus = $app->product_status;
    $countryCode = array_keys($countries)[0];
    $countryName = array_values($countries)[0];
@endphp

<div class="app app-status-{{ $productStatus['status'] }} @if(request()->has('aid')) show  @endif" data-status="{{ $app['status'] }}" data-id="{{ $app['aid'] }}"  id="wrapper-{{ $app['aid'] }}">
    <div class="columns">
        <div class="column column-app-name">
            <p class="name toggle-app">
                {{ $app['display_name'] }}
            </p>
        </div>

        <div class="column column-country">
            <p title="{{ $countryName }}">@svg($countryCode, '#000000', 'images/locations')</p>
        </div>

        <div class="column column-developer-company">
            {{ $details['email'] ?? $details['name'] ?? '' }}
        </div>

        <div class="column column-go-live">
            {{ date('d M Y', strtotime($app['live_at'] ?? $app['updated_at'])) }}
        </div>

        <div class="column column-status">
            <span class="app-status">{{ $productStatus['label'] }}</span>
            <button class="toggle-app-button toggle-app reset">@svg('chevron-down', '#000000')</button>
        </div>
    </div>

    <div class="detail">
        <h2>Product details</h2>
        {{-- @if(!empty($sandboxProducts))
        <div class="products-title">
            <strong>Products</strong>
            <form class="ml-1" action="{{ route('admin.credentials.renew', ['app' => $app, 'type' => 'sandbox']) }}" method="POST" onsubmit="if(confirm('Renewing the credentials will revoke the current ones, do you want to continue?')){addLoading('Renewing credentials...')}else{return false};">
                @csrf
                <button class="outline small">Renew credentials</button>
            </form>
        </div>
        <div class="products">
            <x-apps.products :app="$app" :products="$sandboxProducts['products']" for="staging" />
        </div>
        @endif

        @if(!empty($prodProducts['products']))
        <div class="detail-right"></div>
        <div class="products-title">
            <strong>Production products</strong>
            <form class="ml-1" action="{{ route('admin.credentials.renew', ['app' => $app, 'type' => 'production']) }}" method="POST" onsubmit="if(confirm('Renewing the credentials will revoke the current ones, do you want to continue?')){addLoading('Renewing credentials...')}else{return false};">
                @csrf
                <button class="outline small" href="">Renew credentials</button>
            </form>
        </div>
        <div class="products production-products kyc-status-{{ Str::slug($app->kyc_status ?? 'none') }}">
            <x-apps.products :app="$app" :products="$prodProducts['products']" for="production" />
        </div>
        @endif --}}

        <div class="detail-items">
            <div class="detail-left">
                <h3>Developer details</h3>
                <p>Name: <span class="detail-text">{{ ($details['first_name'] ?? $details['name'] ?? 'Not registered')  . ' ' . ($details['last_name'] ?? '') }}</span></p>
                <p>Email: <a href="mailto:{{ $details['email'] ?? $details['owner']['email'] ?? '' }}">{{ $details['email'] ?? $details['owner']['email'] ?? '' }}</a></p>
                <div class="detail-actions">
                    <form action="{{ route('admin.credentials.renew', ['app' => $app, 'type' => 'sandbox']) }}" method="POST" onsubmit="if(confirm('Renewing the credentials will revoke the current ones, do you want to continue?')){addLoading('Renewing credentials...')}else{return false};">
                        @csrf
                        <button class="reset">@svg('renew') Renew credentials</button>
                    </form>
                </div>
            </div>
            <div class="detail-right">
                <h3>Application details</h3>
                <p>Callback URL: <span class="detail-text">{{ $app['callback_url'] ?: 'No callback url' }}</span></p>
                <p>Description: <span class="detail-text">{{ $app['description'] ?: 'No description' }}</span></p>
                <div class="detail-actions">
                    @if($app['status'] === 'approved')
                    <button class="log-notes reset" data-id="{{ $app['aid'] }}">@svg('revoke') Revoke application</button>
                    @else
                    <button class="log-notes reset" data-id="{{ $app['aid'] }}">@svg('approve') Approve application</button>
                    @endif
                    <button class="log-notes reset" data-id="{{ $app['aid'] }}">@svg('view') View application log notes</button>
                </div>
            </div>
        </div>

        @if(!is_null($app['kyc_status']))
        <div class="kyc-status">
            <strong class="mr-2">Update the KYC status</strong>
            <select name="kyc_status" class="kyc-status-select" data-aid="{{ $app['aid'] }}" autocomplete="off">
                <option @if($app['kyc_status'] === 'Documents Received') selected @endif value="Documents Received">Documents received</option>
                <option @if($app['kyc_status'] === 'In Review') selected @endif value="In Review">In review</option>
                <option @if($app['kyc_status'] === 'KYC Approved') selected @endif value="KYC Approved">KYC approved</option>
            </select>
        </div>
        @endif
    </div>
</div>
