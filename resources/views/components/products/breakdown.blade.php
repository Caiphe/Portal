<div class="specification-responses">
    @foreach($responses as $response)
    <div class="specification-response @if ($loop->last) last @endif">
        @if(!empty($response['schema']))
        <h6 onclick="toggleParent(this)">{{$response['code']}}<span class="ml-1">{{$response['status']}}</span>@svg('minus')@svg('plus')</h6>
        <div class="response-details show-modal">
            <button class="light small modal" onclick="toggleResponseDetail(this, 'modal')">MODAL</button>
            <button class="light small example" onclick="toggleResponseDetail(this, 'example')">EXAMPLE</button>

            <div class="mb-2 detail-modal">
                <x-products.modal :schemas="$response['schema']" />
            </div>
            <div class="mb-2 detail-example"><pre><code>{{json_encode(json_decode($response['body']), JSON_PRETTY_PRINT)}}</code></pre></div>
        </div>
        @else
        <h6>{{$response['code']}}<span class="ml-1">{{$response['status']}}</span></h6>
        @endif
    </div>
    @endforeach
</div>