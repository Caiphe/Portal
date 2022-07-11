@component('mail::message')
# Your OpCo Admin Role request has been approved for the following countries:

@foreach ($data as $item)
 {{ $item }} <br />
@endforeach

Thanks,<br>
{{ config('app.name') }}
@endcomponent
