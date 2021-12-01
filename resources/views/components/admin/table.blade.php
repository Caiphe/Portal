@props(['collection', 'fields', 'modelName'])

@php
    $modelName ??= explode('.', Route::currentRouteName())[1];
@endphp

<div class="cols centre-align mb-2 filter-form-container">
    <form id="product-search-form" action="{{ route("admin.{$modelName}.index") }}" method="GET" class="ajaxify" data-replace="#table-data">
        @if($modelName === 'product')
            <input id="search-page" type="text" value="{{ request()->get('q', '') }}" name="q" placeholder="Search for products..." autofocus autocomplete="off">
        @else
            <input id="search-page" type="text" value="{{ request()->get('q', '') }}" name="q" placeholder="Search..." autofocus autocomplete="off">
        @endif
        @if($modelName === 'product')
        <select name="access" class="access-level" id="access-level">
            <option value="">All access levels</option>
            <option value="public" @if(request()->get('access') === 'public') selected @endif>Public</option>
            <option value="private" @if(request()->get('access') === 'private') selected @endif>Private</option>
        </select>
        @endif
    </form>
        {{-- <button class="button dark kinda fab">@svg('close', '#FFFFFF')</button> --}}
    <form id="reset-search" action="{{ route("admin.{$modelName}.index") }}" method="GET" class="ajaxify" data-replace="#table-data" data-func="clearSearch()">
        <button class="primary kinda custom-right" id="reset-search-filters">Reset Filters</button>
    </form>

</div>

<div id="table-data">
    @include('components.admin.table-data', compact('collection', 'fields', 'modelName'))
</div>

@push('scripts')
<script>
    function removeRow(id) {
        var row = document.querySelector('.' + id);
        row.parentNode.removeChild(row);
    }

    function clearSearch() {
        var searchPage = document.getElementById('search-page');
        searchPage.value = "";
        searchPage.focus();
    }

    var searchProduct = document.getElementById('search-page');
    searchProduct.addEventListener('keyup', filterUsers);
    var timeout = null;

    function filterUsers() {
        if(timeout !== null){
            clearTimeout(timeout);
            timeout = null;
        }
        timeout = setTimeout(submitFilter, 1000);
    }

    function submitFilter() {
        var filterForm =  document.getElementById('product-search-form');
        if(filterForm.requestSubmit !== undefined) {
            filterForm.requestSubmit();
        }else{
            filterForm.submit();
        }
    }
</script>
@endpush
