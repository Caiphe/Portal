@component('mail::message')
# *{{ $data['product'] }}* has been *{{ $data['action'] }}d* by *{{ $currentUser->full_name}}*

## Details
|                   |    |           |
|:------------------|:---|:----------|
| **App** | | {{ $app->display_name }} |
| **Developer** | | {{ $app->developer->full_name ?? $app->team->name }} |

<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
