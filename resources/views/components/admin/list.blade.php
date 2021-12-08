<table>
    <thead>
        <tr>
            @foreach($fields as $specifiedName => $field)
            <th align="left" width="{{ floor(100 / ($loop->count)) }}%">{{ is_string($specifiedName) ? $specifiedName : preg_replace('/[_\.]/', ' ', $field) }}</th>
            @endforeach
            <th align="left" class="action-row">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($collection as $model)
        <tr class="{{ $model->slug }}">
            @foreach($fields as $field)
            @php
                $f = explode(',', $field);
                $item = Arr::get($model, $f[0]);
                $action = count($f) > 1 ? explode(':', $f[1]) : null;
            @endphp
            <td align="left">
                @if(!is_null($action))
                @foreach(explode(',', $item) as $g)
                <span class="{{ Str::slug($g) }}">{{ $g }}</span>
                @endforeach
                @else
                <a href="{{ route("admin.{$modelName}.edit", $model->slug) }}">{{ $item }}</a>
                @endif
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
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $collection->withQueryString()->links() }}