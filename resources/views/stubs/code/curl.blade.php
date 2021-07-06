curl --location --request {{ strtoupper($method) }} '{!! $url !!}'
@foreach ($headers as $header)
    --header '{{ $header['key'] }}: {{ $header['value'] ?: 'string' }}'
@endforeach
@if (isset($parameters['body']['formdata']))
    --data-raw '{
@foreach ($parameters['body']['formdata'] as $formData)
@if (!isset($formData['type']) && gettype($formData) === 'array')
@foreach($formData as $fD)
        "{{ $fD['key'] }}": @reqType($fD['value'], $fD['type']),
@endforeach
@else
        "{{ $formData['key'] }}": @reqType($formData['value'], $formData['type']),
@endif
@endforeach
    }'
@endif