@component('mail::message')
# Opco admin role request denial

Below is the reason why your request has been denied : 

 {{ $data }} 

Thanks,<br>
{{ config('app.name') }}
@endcomponent
