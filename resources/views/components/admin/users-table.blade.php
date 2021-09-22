@props(['collection', 'fields', 'modelName', 'order', 'defaultSortQuery' ])

@php
    $modelName ??= explode('.', Route::currentRouteName())[1];
@endphp

<div class="cols centre-align mb-2 filter-form-container">
    <form id="users-search-form" action="{{ route("admin.{$modelName}.index") }}" method="GET" class="ajaxify custom-users-form-filter" data-replace="#table-data">
        <input id="search-page" type="text" name="q" placeholder="Search for users..." autofocus autocomplete="off">
        <select name="verified" class="users-status">
            <option value="">Select by Status</option>
            <option value="verified">Verified</option>
            <option value="not_verified">Not Verified</option>
        </select>
        <select name="per-page" class="show-per-page">
            <option value="10">Show 10 per page</option>
            <option value="25">Show 25 per page</option>
            <option value="50">Show 50 per page</option>
        </select>
    </form>
        {{-- <button class="button dark kinda fab">@svg('close', '#FFFFFF')</button> --}}
    <form id="reset-search" action="{{ route("admin.{$modelName}.index") }}" method="GET" class="ajaxify" data-replace="#table-data" data-func="clearSearch()">
        <button class="primary kinda custom-right">Reset Filters</button>
    </form>

</div>

<div id="table-data">
    @include('components.admin.users-data', compact('collection', 'fields', 'modelName', 'order', 'defaultSortQuery'))
</div>

@push('scripts')
<script>
    function removeRow(id) {
        var row = document.querySelector('.' + id);
        row.parentNode.removeChild(row);
    }

    function clearSearch() {
        var searchPage = document.getElementById('search-page');
        
        document.getElementById('users-search-form').reset();
        searchPage.focus();
    }
</script>
@endpush
