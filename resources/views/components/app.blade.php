@allowonce('card_link')
<link href="/css/components/app.css" rel="stylesheet"/>
@endallowonce

@props(['app', 'attr'])

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
        {{ $app['callbackUrl'] }}
    </div>
    <div class="column">
        {{ date('d M Y', end($app['credentials'])['issuedAt'] / 1000) }}
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
                <div class="consumer">
                    <p>Test</p>
                    <p>Test</p>
                </div>
                <div class="copy-column"></div>
                <div>
                    <p><strong>Regions:</strong></p>
                </div>
                <div>
                    <div>
                        @svg('za', '#000000', 'images/locations')
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
                    <p><strong>Regions:</strong></p>
                    <p><strong>Key issued:</strong></p>
                    <p><strong>Expires:</strong></p>
                </div>
                <div>
                    <div>
                        @svg('za', '#000000', 'images/locations')
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
            <button class="dashboard-approve">Approve all</button>
            <button class="dashboard-revoke">Revoke all</button>
            <button class="dashboard-complete">Complete</button>
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
