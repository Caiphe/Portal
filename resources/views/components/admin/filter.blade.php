@props(['searchTitle' => 'Search'])

@php
    $numberPerPage = request()->get('number_per_page', '15');
@endphp

<form id="search-form" action="" method="GET" class="ajaxify" data-replace="#table-data">
    <button id="search-form-toggle" class="sl-button reset">@svg('filter')@svg('close') Filters</button>

    <label class="filter-item" for="search-page">
        {{ $searchTitle }}
        <input id="search-page" type="text" value="{{ request()->get('q', '') }}" name="q" placeholder="Search..." autofocus autocomplete="off">
    </label>

    {{ $slot }}

    <label class="filter-item" for="number-per-page">
        Number of results per page
        <select name="number_per_page" id="number-per-page" autocomplete="off">
            <option value="15" @if($numberPerPage === '15') selected @endif>Show 15 per page</option>
            <option value="25" @if($numberPerPage === '25') selected @endif>Show 25 per page</option>
            <option value="50" @if($numberPerPage === '50') selected @endif>Show 50 per page</option>
        </select>
    </label>
</form>