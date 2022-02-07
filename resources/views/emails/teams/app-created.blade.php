@component('mail::message')
# A new team app from {{ $app->country->name }} has been created

## Details
|                   |    |           |
|:------------------|:---|:----------|
| **App** | | {{ $app->display_name }} |
| **Country** | | {{ $app->country->name }} |
| **App Creator** | | {{ $app->developer->full_name ?? $app->team->name }} |
| **Creator Email** | | <a href="mailto:{{ $app->developer->email ?? $app->team->owner->email }}">{{ $app->developer->email ?? $app->team->owner->email }}</a> |
| **Team Name** | | {{ $app->team->name }} |
| **Products** | | {{ $app->products->implode('display_name', ', ') }} |

@component('mail::button', ['url' => route('admin.dashboard.index')])
Go to the dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
