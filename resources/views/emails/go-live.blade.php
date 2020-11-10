@component('mail::message')
# New go live for **{{ strtoupper($data['group']) }}**

## App
**{{ $data['app']->display_name }}**

## User details

|Field              |    |Response   |
|:------------------|:---|:----------|
@foreach($data as $key => $value)
@if($key === 'files' || $key === 'app' || $key === 'group' || $key === 'accept') @continue @endif
|{{ucwords(str_replace('_', ' ', $key))}}:| |{{$value}}|
@endforeach

<br>
## Products
{{ $data['app']->products->pluck('display_name')->implode(', ') }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
