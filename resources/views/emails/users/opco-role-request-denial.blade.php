@component('mail::message')
# Below is the reason why your request has been denied : 

 {{ $data }} 

Thanks,<br>
{{ config('app.name') }}
@endcomponent
