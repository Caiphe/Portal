<table>
    <thead>
        <tr>
            @foreach($fields as $specifiedName => $field)
            <th align="left" class="{{ preg_replace('/.*?addClass:(.*?)\|?/', '$1', $field) }}"><a href="?sort={{ strtok($field, '|') }}&order={{ $order ?? 'desc' }}">{{ is_string($specifiedName) ? $specifiedName : preg_replace('/[_\.]/', ' ', ucfirst($field)) }} @svg('chevron-sorter')</a></th>
            @endforeach
            <th align="left" class="action-row">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($collection as $model)
        <tr>
            @foreach($fields as $field)
            <td align="left" class="{{ preg_replace('/.*?addClass:(.*?)\|?/', '$1', $field) }}">
                @listFunc($field, $model)
            </td>
            @endforeach
            <td class="action-row">
                @if(Route::has("{$modelName}.show"))
                <a href="{{ route("{$modelName}.show", $model->slug) }}" target="_blank" rel="noreferrer">@svg('eye') View</a>
                @endif
                <a href="{{ route("admin.{$modelName}.edit", $model->slug) }}">@svg('pencil') Edit</a>
                @if(Route::has("admin.{$modelName}.delete"))
                <form class="delete-form ajaxify" action="{{ route("admin.{$modelName}.delete", $model->slug) }}" method="POST" data-func="removeRow({{ $model->slug }})" data-confirm="Are you sure you want to delete this?">
                    @method('DELETE')
                    @csrf
                    <button class="sl-button">@svg('trash') Delete</button>
                </form>
                @endif
                <button class="sl-button reset mobile-action">@svg('more-vert')@svg('chevron-right')</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $collection->withQueryString()->links() }}