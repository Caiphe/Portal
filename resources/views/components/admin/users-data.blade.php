<table>
    <thead>
        <tr>
            <th><a style="color: #ffffff" href="?sort=first_name&order={{ $order . $defaultSortQuery }}">Fist Name @svg('chevron-sorter', '#fff')</a></th>
            <th><a style="color: #ffffff" href="?sort=last_name&order={{  $order . $defaultSortQuery  }}">Last Name @svg('chevron-sorter', '#fff')</a></th>
            <th><a style="color: #ffffff" href="?sort=email&order={{  $order . $defaultSortQuery  }}">Email @svg('chevron-sorter', '#fff')</a></th>
            <th>Member Since</th>
            <th>Role</th>
            <th>Status</th>
            <th>Apps</th>
        </tr>
    </thead>
    <tbody class="users-table-body">
        @foreach($collection as $model)
        <tr class="{{ $model->slug }}">
            @foreach($fields as $field)
                @if($field === 'member_since')
                    <td align="left">
                        {{ $model->created_at->format('Y-m-d') }}
                    </td>
                @elseif($field === 'status')
                    <td align="left">
                        {{ !is_null($model->email_verified_at) ? 'Active' : 'Non-Active' }}
                    </td>
                @elseif($field === 'role')
                    <td align="left">
                        {{ $model->roles_list }}
                    </td>
                @elseif($field === 'apps')
                    <td align="left">
                        <a href="{{ route("admin.dashboard.index", ['q' => $model->email]) }}">{{ $model->getDeveloperAppsCount() }}</a>
                    </td>
                @else
                    <td align="left">
                        <a href="{{ route("admin.{$modelName}.edit", $model->slug) }}">{{ Arr::get($model, $field) }}</a>
                    </td>
                @endif
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
{{ $collection->withQueryString()->links() }}
