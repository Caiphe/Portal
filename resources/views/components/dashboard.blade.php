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

<div class="app app-status-{{ $productStatus['status'] }} @if(request()->has('aid')) show  @endif"
     data-status="{{ $app['status'] }}" data-id="{{ $app['aid'] }}" id="wrapper-{{ $app['aid'] }}">
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
            {{ date('d M Y', strtotime($app['created_at'])) }}

        </div>

        <div class="column column-status">
            <span class="app-status" aria-label="{{ $productStatus['label'] }}"
                  data-pending="{{ $productStatus['pending'] }}"></span>
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
                <button class="reset environment environment-production" data-environment="production">Production
                </button>
            @endif
            @if($hasSandboxProducts)
                <button class="reset environment environment-sandbox" data-environment="sandbox">Sandbox</button>
            @endif
        </div>

        @if($hasProdProducts)
            <div class="app-products production-products active">
                <form class="renew-credentials"
                      action="{{ route('admin.credentials.renew', ['app' => $app, 'type' => 'production']) }}"
                      method="POST">
                    @csrf
                    <button class="reset renew">@svg('renew') Renew production credentials</button>
                </form>

                <x-admin.dashboard.products :app="$app" :products="$prodProducts['products']" for="production"/>
            </div>
        @endif

        @if($hasSandboxProducts)
            <div class="app-products sandbox-products">
                <form class="renew-credentials"
                      action="{{ route('admin.credentials.renew', ['app' => $app, 'type' => 'sandbox']) }}"
                      method="POST">
                    @csrf
                    <button class="reset renew">@svg('renew') Renew sandbox credentials</button>
                </form>

                <x-admin.dashboard.products :app="$app" :products="$sandboxProducts['products']" for="staging"/>
            </div>
        @endif

        <div class="main-details-items">
            <div class="detail-items">
                <div class="detail-left">
                    <h3>Application details</h3>
                    <p>Callback URL: @if($app['callback_url'])
                            <a href="{{ $app['callback_url'] }}" target="_blank"
                               rel="noopener noreferrer">{{ $app['callback_url'] }}</a>
                        @else
                            <span class="detail-text"> No callback url</span>
                        @endif</p>
                    <p>Description: <span class="detail-text">{{ $app['description'] ?: 'No description' }}</span></p>
                    @if(!is_null($app['kyc_status']))
                        <p>
                            Update the KYC status:
                            <select name="kyc_status" class="kyc-status-select" data-aid="{{ $app['aid'] }}"
                                    autocomplete="off">
                                <option @if($app['kyc_status'] === 'Documents Received') selected
                                        @endif value="Documents Received">Documents received
                                </option>
                                <option @if($app['kyc_status'] === 'In Review') selected @endif value="In Review">In
                                    review
                                </option>
                                <option @if($app['kyc_status'] === 'KYC Approved') selected @endif value="KYC Approved">
                                    KYC approved
                                </option>
                            </select>
                        </p>
                    @endif

                </div>

                <div class="detail-right">
                    <h3>Developer details</h3>
                    @if(isset($details['developer_id']))
                        <p>Name: <a href="{{ route('admin.user.edit', $details) }}" target="_blank"
                                    rel="noopener noreferrer">{{ $details->full_name ?? 'User not in portal' }}</a></p>
                    @else
                        <p>Name: <span class="detail-text">{{ $details->full_name ?? 'User not in portal' }}</span></p>
                    @endif
                    <p>Email address: @if(isset($details->email))
                            <a href="mailto:{{ $details->email }}">{{ $details->email }}</a>
                        @endif</p>
                </div>
            </div>

            {{-- Custom attribe data to go here  TODO reuse the code --}}
            <div id="custom-attributes-list-partial-{{ $app->aid }}">
                {{-- @include('partials.custom-attributes.list', ['app' => $app])--}}
            </div>

            <div class="detail-actions">
                @if($app['status'] === 'approved')
                    <button class="reset app-status-action" data-id="{{ $app['aid'] }}"
                            data-app-display-name="{{ $app['display_name'] }}" data-status="revoked"
                            data-action="{{ route('admin.app.status-update', $app) }}">@svg('revoke') Revoke application
                    </button>
                @else
                    <button class="reset app-status-action" data-id="{{ $app['aid'] }}"
                            data-app-display-name="{{ $app['display_name'] }}" data-status="approved"
                            data-action="{{ route('admin.app.status-update', $app) }}">@svg('approve') Approve
                        application
                    </button>
                @endif
                <button class="log-notes reset" data-id="{{ $app['aid'] }}">@svg('eye') View application log notes
                </button>

            </div>

            <!--======Attributes Section====================================================================================-->
            <div class="app-custom-attributes">
                {{--=================Custom Attributes================--}}

                <div class=main-ca>
                    <div class="main-ca__heading">
                        <span class="customAttributeMain__text">Custome Attributes</span>
                        <button class="btn-show-attribute-modal" data-id="{{ $app->aid }}">
                            Add attribute
                        </button>
                    </div>
                </div>
                <div class="ca-section">
                    <table class="app-attribute-table">
                        <thead class="ca-thead">
                        <tr class="ca-align-left">
                            <th class="custom-attribute--table_th">
                                <a href="#">Name @svg('chevron-sorter')</a>
                            </th>
                            <th class="custom-attribute--table_th not-on-mobile">
                                <a href="#">Value @svg('chevron-sorter')</a>
                            </th>
                            <th class="custom-attribute--table_th not-on-mobile">
                                <a href="#">Type @svg('chevron-sorter')</a>
                            </th>
                            <th class="custom-attribute--table_th">
                                <a href="#">Actions @svg('chevron-sorter')</a>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            // Filter attributes, excluding specified keys
                            $filteredAttributes = collect($app->attributes)->except(['Country', 'TeamName', 'location', 'Description', 'DisplayName', 'AutoRenewAllowed', 'PermittedSenderIDs', 'senderMsisdn']);
                        @endphp

                        @forelse ($filteredAttributes as $key => $value)

                            @php
                                // Initialize display variables
                                $displayName = $key;
                                $attributeType = ''; // No default type
                                $displayValue = $value; // Initialize displayValue

                                // Check if value is an array and convert it to JSON for display
                                if (is_array($value)) {
                                    $attributeType = 'Array';
                                    $displayValue = json_encode($value); // Convert array to JSON string for display
                                }
                            @endphp

                            <tr class="ca-trow">
                                <td class="display_name">
                                    {!! htmlspecialchars($displayName) !!}
                                </td>
                                <td class="not-on-mobile">
                                    {!! htmlspecialchars($displayValue) !!}
                                </td>
                                <td class="not-on-mobile">
                                    @if($displayValue === 'true' || $displayValue === 'false')
                                        Boolean
                                    @elseif (is_string($value) && strpos($value, ',') !== false)
                                        CSV String Array
                                    @elseif (is_string($value) && strpos($value, ',') !== true)
                                        String
                                    @endif
                                </td>
                                <td class="action-row">
                                    <a class="btn-show-edit-attribute-modal"
                                       style="cursor: pointer"
                                       data-edit-id="{{ $app->aid }}"
                                       data-attribute='@json(["name" => $displayName, "value" => $displayValue, "type" => $attributeType])'>
                                        @svg('edit') Edit
                                    </a>

                                    <a class="btn-delete-attribute-modal"
                                       style="cursor: pointer"
                                       data-delete-id="{{ $app->aid }}"
                                       data-attribute-key="{{ $displayName }}"
                                       data-attribute-value="{{ $displayValue }}">
                                        @svg('delete') Delete
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <div class="no-custom-attribute">No custom attribute added yet.</div>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{--=================Reserved Attributes================--}}

                <div class=main-ca>
                    <div class="main-ca__heading">
                        <span class="customAttributeMain__text">Reserved Attributes</span>
                        @php
                            // Filter reserved attributes, specified keys
                            $countReservedAttributes = collect($app->attributes)->only(['AutoRenewAllowed', 'PermittedSenderIDs', 'senderMsisdn'])->count();
                        @endphp
                        <button class="btn-show-attribute-modal btn-show-reserved-attribute-modal"
                                reserved-data-id="{{ $app->aid }}"
                                @if($countReservedAttributes === 3) disabled @endif>
                            Add reserved attribute
                        </button>
                    </div>
                </div>
                <div class="ca-section">
                    <table class="app-attribute-table">
                        <thead class="ca-thead">
                        <tr class="ca-align-left">
                            <th class="custom-attribute--table_th">
                                <a href="#">Name @svg('chevron-sorter')</a>
                            </th>
                            <th class="custom-attribute--table_th not-on-mobile">
                                <a href="#">Value @svg('chevron-sorter')</a>
                            </th>
                            <th class="custom-attribute--table_th not-on-mobile">
                                <a href="#">Type @svg('chevron-sorter')</a>
                            </th>
                            <th class="custom-attribute--table_th">
                                <a href="#">Actions @svg('chevron-sorter')</a>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            // Filter reserved attributes, specified keys
                            $filteredAttributes = collect($app->attributes)->only(['AutoRenewAllowed', 'PermittedSenderIDs', 'senderMsisdn']);
                        @endphp

                        @forelse ($filteredAttributes as $key => $value)
                            @php
                                // Initialize display variables
                                $displayName = $key;
                                $attributeType = ''; // No default type
                                $displayValue = $value; // Initialize displayValue

                                // Check if value is an array and convert it to JSON for display
                                if (is_array($value)) {
                                    $attributeType = 'Array';
                                    $displayValue = json_encode($value); // Convert array to JSON string for display
                                }
                            @endphp

                            <tr class="ca-trow">
                                <td class="display_name">
                                    {!! htmlspecialchars($displayName) !!}
                                </td>
                                <td class="not-on-mobile">
                                    {!! htmlspecialchars($displayValue) !!}
                                </td>
                                <td class="not-on-mobile">
                                    @if($displayValue === 'true' || $displayValue === 'false')
                                        Boolean
                                    @elseif (is_string($value) && strpos($value, ',') !== false)
                                        CSV String Array
                                    @elseif (is_string($value) && strpos($value, ',') !== true)
                                        String
                                    @endif
                                </td>
                                <td class="action-row">
                                    <a class="btn-show-edit-reserved-attribute-modal"
                                       style="cursor: pointer"
                                       data-edit-id="{{ $app->aid }}"
                                       data-attribute='@json(["name" => $displayName, "value" => $displayValue, "type" => $attributeType])'>
                                        @svg('edit') Edit
                                    </a>
                                    <a class="btn-delete-attribute-modal"
                                       style="cursor: pointer"
                                       data-delete-id="{{ $app->aid }}"
                                       data-attribute-key="{{ $displayName }}"
                                       data-attribute-value="{{ $displayValue }}">
                                        @svg('delete') Delete
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <div class="no-custom-attribute">No reserved attribute added yet.</div>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{--=================System Attributes================--}}

                <div class=main-ca>
                    <div class="main-ca__heading">
                        <span class="customAttributeMain__text">System Attributes</span>

                    </div>
                </div>
                <div class="ca-section">
                    <table class="app-attribute-table">
                        <thead>
                        <tr class="ca-align-left">
                            <th class="custom-atrribute--table_th "><a href="#">Name @svg('chevron-sorter')</a>
                            </th>
                            <th class="custom-atrribute--table_th"><a href="#">Value @svg('chevron-sorter')</a>
                            </th>
                            <th class="custom-atrribute--table_th"><a href="#">Type @svg('chevron-sorter')</a>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="ca-tbody">
                        @php
                            $filteredAttributes = collect($app->attributes)->only(['Country', 'TeamName', 'location', 'Description', 'DisplayName']);
                        @endphp
                        @forelse ($filteredAttributes as $key => $value)
                            <tr class="ca-trow">
                                <td class="display_name">
                                    {{ $key }}
                                </td>
                                <td class="not-on-mobile">
                                    {{ $value }}
                                </td>
                                <td class="not-on-mobile">
                                    @if($value === 'true' || $value === 'false')
                                        Boolean
                                    @elseif (is_string($value) && strpos($value, ',') !== false)
                                        CSV String Array
                                    @elseif (is_string($value) && strpos($value, ',') !== true)
                                        String
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <div class="no-custom-attribute">No system attribute added yet.</div>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
            <!--======End Attribute Section======================================================================================-->
        </div>

    </div>
</div>
