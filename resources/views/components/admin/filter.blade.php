@props(['searchTitle' => 'Search'])

<div class="filter-form-container">
    <form id="search-form" action="" method="GET" class="ajaxify" data-replace="#table-data">
        <label class="filter-item" for="search-page">
            {{ $searchTitle }}
            <input id="search-page" type="text" value="{{ request()->get('q', '') }}" name="q" placeholder="Search..." autofocus autocomplete="off">
        </label>

        {{ $slot }}
    </form>
</div>