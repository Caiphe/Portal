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
{{ $collection->links() }}
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
        <tr>
            @foreach($fields as $field)
            <td>
                <a href="{{ route("admin.{$modelName}.edit", $model->slug) }}">{{ Arr::get($model, $field) }}</a>
            </td>
            @endforeach
            <td align="center">
                <a href="{{ route("admin.{$modelName}.edit", $model->slug) }}">@svg('edit')</a>
                <a href="{{ route("{$modelName}.show", $model->slug) }}" target="_blank" rel=”noreferrer”>@svg('visible')</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $collection->withQueryString()->links() }}