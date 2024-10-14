@component('mail::message')

# Sync completed successfully.

Below are all the details:

## Products
|                   |    |           |
|:------------------|:---|:----------|
| **Deleted Products** | | {{ $deletedProducts }} |
| **Added Products** | | {{ $addedProducts }} |
| **Total Products** | | {{ $totalProducts }} |
---
## Apps
|                   |    |           |
|:------------------|:---|:----------|
| **Total Apps** | | {{ $totalApps }} |

@if($noCredsApps)
**Apps With No Credentials:** <br />
@foreach ($noCredsApps as $apps)
- {{ $apps }} <br/>
@endforeach
<br />
@endif

@endcomponent
