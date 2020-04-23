@component('mail::message')
New message from the MTN Developer Portal contact form.

|Field              |    |Response   |
|:------------------|:---|:----------|
@foreach($data as $key => $value)
|{{ucwords(str_replace('_', ' ', $key))}}:| |{{$value}}|
@endforeach
@endcomponent
