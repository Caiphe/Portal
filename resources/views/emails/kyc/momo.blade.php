@component('mail::message')
# New go live for **MoMo**

App: [{{ $data['app']->display_name }}]({{ config('app.url') }}/admin/dashboard?q={{ $data['app']->aid }})

## User details

|Field              |    |Response   |
|:------------------|:---|:----------|
@foreach($data as $key => $value)
@if($key === 'files' || $key === 'app' || $key === 'group' || $key === 'accept') @continue @endif
|{{ucwords(str_replace('_', ' ', $key))}}:| |{{$value}}|
@endforeach

<br>

## Products

@foreach($data['app']->products as $product)
[{{ $product->display_name }}]({{ config('app.url') }}/products/{{ $product->slug }})<br>
@endforeach

Thanks,<br>
{{ config('app.name') }}
@endcomponent
