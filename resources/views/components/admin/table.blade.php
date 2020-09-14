@props(['collection', 'fields', 'modelName'])

@php
    $modelName ??= explode('.', Route::currentRouteName())[1];
@endphp

<div class="cols centre-align mb-2">
    <form action="{{ route("admin.{$modelName}.index") }}" method="GET" class="ajaxify" data-replace="#table-data">
        <input type="text" name="q" placeholder="Search" autofocus autocomplete="off">
    </form>
    <form action="{{ route("admin.{$modelName}.index") }}" method="GET" class="ajaxify" data-replace="#table-data">
        <button class="button outline dark small ml-1">reset</button>
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
</script>
@endpush