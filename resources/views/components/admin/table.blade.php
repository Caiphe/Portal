@props(['collection', 'fields', 'modelName' => (Str::singular($collection[0]->getTable()))])

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
{{ $collection->links() }}