<pre><code>
curl --location --request {{ strtoupper($method) }} '{!! $url !!}'
@foreach ($headers as $header)
    --header '{{ $header['key'] }}: {{ $header['value'] ?: 'string' }}'
@endforeach
@if (isset($parameters['body']['details']))
    --data-raw '{
@foreach ($parameters['body']['details'] as $details)
@if (!isset($details['type']) && gettype($details) === 'array')
@foreach($details as $detail)
        "{{ $detail['key'] }}": @reqType($detail['value'], $detail['type']),
@endforeach
@else
        "{{ $details['key'] }}": @reqType($details['value'], $details['type']),
@endif
@endforeach
    }'
@endif
</code></pre>