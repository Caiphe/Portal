@props(['collection', 'fields', 'modelName'])

@php
    $modelName ??= explode('.', Route::currentRouteName())[1];
@endphp

<div class="cols centre-align mb-2">
    <form action="{{ route("admin.{$modelName}.index") }}" method="GET" class="ajaxify" data-replace="#table-data">
        <input id="search-page" type="text" name="q" placeholder="Search" autofocus autocomplete="off">
    </form>
    <form id="reset-search" action="{{ route("admin.{$modelName}.index") }}" method="GET" class="ajaxify" data-replace="#table-data" data-func="clearSearch()">
        <button class="button dark kinda fab">@svg('close', '#FFFFFF')</button>
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
</script>
@endpush