@component('mail::message')
# A new app has been created

## Details
|                   |    |           |
|:------------------|:---|:----------|
| **App** | | {{ $app->display_name }} |
| **Developer** | | {{ $app->developer->full_name }} |
| **Products** | | {{ $app->products->implode('display_name', ', ') }} |

@component('mail::button', ['url' => route('admin.dashboard.index')])
Go to the dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
