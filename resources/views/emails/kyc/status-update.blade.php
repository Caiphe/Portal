@component('mail::message')
# Hi {{ $developer->full_name }}

The KYC status for **{{ $app->display_name }}** has been updated to **{{ $app->kyc_status }}**.

@component('mail::button', ['url' => route('app.index')])
View you apps
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
