@csrf

@include('templates.admin.users.form')

<div class="apps-filter">
    <h2>Developer's apps</h2>
    <label>
        <h3>Country</h3>
        <select name="country-filter" id="country-filter">
            <option value="all">All</option>
            @foreach($userApps->pluck('country')->unique('name') as $country)
                @if(!$country || (!in_array($country->code, $currentUserResponsibleCountries) && !$isAdminUser)) @continue @endif
                <option value="{{ $country->code }}" {{ (($selectedCountryFilter === $country->code) ? 'selected': '') }}>{{ $country->name }}</option>
            @endforeach
        </select>
    </label>
</div>

{{-- apps list --}}
<table id="apps-list" class="table-list">
    <thead>
        <tr>
            <th><a href="?sort=name&order={{ $order }}">Name @svg('chevron-sorter')</a></th>
            <th><a href="?sort=products_count&order={{ $order }}">Products @svg('chevron-sorter')</a></th>
            <th><a href="?sort=created_at&order={{ $order }}">Registered @svg('chevron-sorter')</a></th>
            <th><a href="?sort=country_code&order={{ $order }}">Country @svg('chevron-sorter')</a></th>
            <th><a href="?sort=status&order={{ $order }}">Status @svg('chevron-sorter')</a></th>
        </tr>
    </thead>

    @if(!$userApps->isEmpty())
        @foreach($userApps as $app)
        @php
            $productStatus = $app->product_status;
        @endphp
        @if(!(in_array($app->country_code, $currentUserResponsibleCountries) || $isAdminUser)) @continue @endif
        <tr class="user-app" data-country="{{ $app->country_code }}">
            <td><a href="{{ route('admin.dashboard.index', ['aid' => $app]) }}" class="app-link">{{ $app->display_name }}</a></td>
            <td class="not-on-mobile">{{ $app->products_count }}</td>
            <td class="not-on-mobile">{{ $app->created_at->format('d M Y') }}</td>
            <td class="not-on-mobile"><img class="country-flag" src="/images/locations/{{ $app->country_code ?? 'globe' }}.svg" alt="Country flag"></td>
            <td>
                <div class="status app-status-{{ $productStatus['status'] }}" aria-label="{{ $productStatus['label'] }}" data-pending="{{ $productStatus['pending'] }}"></div>
                <a class="go-to-app" href="{{ route('admin.dashboard.index', ['aid' => $app]) }}">@svg('chevron-right')</a>
            </td>
        </tr>
        @endforeach
    @else
    <tr>
        <td>Developer currently has no apps</td>
    </tr>
    @endif
</table>

@push('scripts')
    <script src="{{ mix('/js/templates/admin/users/scripts.js') }}"></script>
@endpush
