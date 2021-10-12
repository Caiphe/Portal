@props(['collection', 'fields', 'modelName', 'order', 'defaultSortQuery' ])

@php
    $modelName ??= explode('.', Route::currentRouteName())[1];
@endphp

<div class="cols centre-align mb-2 filter-form-container">
    <form id="users-search-form" action="{{ route("admin.{$modelName}.index") }}" method="GET" class="ajaxify custom-users-form-filter" data-replace="#table-data">
        <input id="search-page" type="text" name="q" placeholder="Search for users..." autofocus autocomplete="off">
        <select name="verified" class="users-status">
            <option value="">Select by Status</option>
            <option value="verified" @if(request()->get('verified') === 'verified') selected @endif>Verified</option>
            <option value="not_verified" @if(request()->get('verified') === 'not_verified') selected @endif>Not Verified</option>
        </select>
        <select name="per-page" class="show-per-page">
            <option value="10" @if(request()->get('per-page') === '10') selected @endif>Show 10 per page</option>
            <option value="25" @if(request()->get('per-page') === '25') selected @endif>Show 25 per page</option>
            <option value="50" @if(request()->get('per-page') === '50') selected @endif>Show 50 per page</option>
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
    var searchUser = document.getElementById('search-page');

    function removeRow(id) {
        var row = document.querySelector('.' + id);
        row.parentNode.removeChild(row);
    }

    function clearSearch() {
        document.getElementById('search-page').value = '';
        document.querySelector('.users-status').selectedIndex = 0;
        document.querySelector('.show-per-page').selectedIndex = 0;

        searchUser.focus();
    }

    searchUser.addEventListener('keyup', filterUsers);
    var timeout = null;

    function filterUsers() {
        if(timeout !== null){
            clearTimeout(timeout);
            timeout = null;
        }

        timeout = setTimeout(submitFilter, 1000);
    }

    function submitFilter() {
        var filterForm =  document.getElementById('users-search-form');
        if(filterForm.requestSubmit !== undefined) {
            filterForm.requestSubmit();
        }else{
            filterForm.submit();
        }
    }

</script>
@endpush
