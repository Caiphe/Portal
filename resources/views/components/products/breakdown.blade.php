<div class="specification-responses">
    @foreach($responses as $response)
    <div class="specification-response @if ($loop->last) last @endif">
        <h6>{{$response['code']}}<span class="ml-1">{{$response['status']}}</span>@svg('plus')</h6>
        <div class="response-details">
            <button class="light small modal">MODAL</button><button class="outline light small example">EXAMPLE</button>
            <div class="mb-2 detail-modal"></div>
            <div class="mb-2 detail-example"><pre><code>{{json_encode(json_decode($response['body']), JSON_PRETTY_PRINT)}}</code></pre></div>
        </div>
    </div>
    @endforeach
</div>