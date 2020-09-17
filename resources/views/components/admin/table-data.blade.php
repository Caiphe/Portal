<table>
    <thead>
        <tr>
            @foreach($fields as $specifiedName => $field)
            <th align="left">{{ gettype($specifiedName) === 'string' ? $specifiedName : preg_replace('/[_\.]/', ' ', $field) }}</th>
            @endforeach
            <th width="92">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($collection as $model)
        <tr class="{{ $model->slug }}">
            @foreach($fields as $field)
            <td align="left">
                <a href="{{ route("admin.{$modelName}.edit", $model->slug) }}">{{ Arr::get($model, $field) }}</a>
            </td>
            @endforeach
            <td align="center">
                <a href="{{ route("admin.{$modelName}.edit", $model->slug) }}">@svg('edit', '#053241')</a>
                @if(Route::has("admin.{$modelName}.show"))
                <a href="{{ route("{$modelName}.show", $model->slug) }}" target="_blank" rel="noreferrer">@svg('eye', '#053241')</a>
                @endif
                @if(Route::has("admin.{$modelName}.delete"))
                <form class="delete-form ajaxify" action="{{ route("admin.{$modelName}.delete", $model->slug) }}" method="POST" data-func="removeRow({{ $model->slug }})" data-confirm="Are you sure you want to delete this?">
                    @method('DELETE')
                    @csrf
                    <button>@svg('delete', '#053241')</button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $collection->withQueryString()->links() }}