@props(['app', 'appStagingProducts', 'details', 'type', 'attr', 'countries'])

@php
    $isAdminPage = Request::is('admin/*');
    $credentials = $app['credentials'];
    [$sandboxProducts, $prodProducts] = $app->getProductsByCredentials();
    $appStatus = $app->products->filter(fn($prod) => $prod->pivot->status === 'pending')->count() > 0 ? 'pending' : $app['status'];
    $countryCode = array_keys($countries)[0];
    $countryName = array_values($countries)[0];
@endphp

@allowonce('card_link')
<link href="{{ mix('/css/components/_app.css') }}" rel="stylesheet"/>
@endallowonce

<div class="app app-status-{{ $appStatus }}" data-name="{{ $app['name'] }}" data-id="{{ $app['aid'] }}" data-developer="{{ $app['developer']['first_name'] ?? '' }}"
     data-locations="{{ $countryCode }}">
    <div class="column">
        <p class="name toggle-app">
            <span title="{{ $appStatus }}" class="status-icon"></span>
            <span class="app-name">  {{ $app['display_name'] }}</span>
        </p>
    </div>

    <div class="column">
        @if($isAdminPage)
            {{ $details['email'] ?? '' }}
        @else
           <a class="bold" href="" target="_blank"> @subStr($app['callback_url'], 30)</a>
        @endif
    </div>

    @if($type === 'approved')
    <div class="column countries">
       @svg($countryCode, '#000000', 'images/locations')
    </div>
    @else
        <div class="column"></div>
    @endif


    <div class="column">
        @if($isAdminPage)
            {{ date('Y-m-d', strtotime($app['live_at'] ?? $app['updated_at'])) }}
        @else
            {{ date('Y-m-d', strtotime($app['updated_at'])) }}
        @endif
    </div>

    <div class="column">
        @if($isAdminPage)
            {{ date('Y-m-d', strtotime($app['live_at'] ?? $app['updated_at'])) }}
        @else
            {{ date('Y-m-d', strtotime($app['updated_at'])) }}
        @endif
    </div>
    
    {{-- <div class="column">
        <button class="actions"></button>
    </div> --}}

    <div class="modal"></div>
</div>
