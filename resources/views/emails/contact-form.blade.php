@component('mail::message')

Hi,
You have received an email from contact form on {{ config('app.name') }}

{{$email['message']}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
