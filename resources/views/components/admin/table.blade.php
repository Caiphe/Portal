@props(['collection', 'fields', 'modelName'])

@php
    $modelName ??= explode('.', Route::currentRouteName())[1];
@endphp

<div class="cols centre-align mb-2">
    <form action="{{ route("admin.{$modelName}.index") }}" method="GET">
        <input type="text" name="q" placeholder="Search" autofocus autocomplete="off">
    </form>
    <a href="{{ route("admin.{$modelName}.index") }}" class="button outline dark small ml-1">reset</a>
</div>
{{ $collection->withQueryString()->links() }}
<table>
    <thead>
        <tr>
            @foreach($fields as $specifiedName => $field)
            <th>{{ gettype($specifiedName) === 'string' ? $specifiedName : preg_replace('/[_\.]/', ' ', $field) }}</th>
            @endforeach
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($collection as $model)
        <tr class="{{ $model->slug }}">
            @foreach($fields as $field)
            <td>
                <a href="{{ route("admin.{$modelName}.edit", $model->slug) }}">{{ Arr::get($model, $field) }}</a>
            </td>
            @endforeach
            <td align="center">
                <a href="{{ route("admin.{$modelName}.edit", $model->slug) }}">@svg('edit')</a>
                <a href="{{ route("{$modelName}.show", $model->slug) }}" target="_blank" rel="noreferrer">@svg('eye')</a>
                @if(Route::has("admin.{$modelName}.delete"))
                <form class="delete-form ajaxify" action="{{ route("admin.{$modelName}.delete", $model->slug) }}" method="POST" data-func="removeRow({{ $model->slug }})" data-confirm="Are you sure you want to delete this?">
                    @method('DELETE')
                    @csrf
                    <button>@svg('delete')</button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $collection->withQueryString()->links() }}

@push('scripts')
<script>
    function removeRow(id) {
        var row = document.querySelector('.' + id);

        row.parentNode.removeChild(row);
    }
</script>
@endpush