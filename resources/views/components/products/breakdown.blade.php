<div class="specification-responses">
    @foreach($responses as $response)
    <div class="specification-response @if ($loop->last) last @endif">
        <h6>{{$response['code']}}<span class="ml-1">{{$response['status']}}</span>@svg('plus')</h6>
        <div class="response-details show-modal">
            <button class="light small modal" onclick="toggleResponseDetail(this, 'modal')">MODAL</button>
            <button class="outline light small example" onclick="toggleResponseDetail(this, 'example')">EXAMPLE</button>

            <div class="mb-2 detail-modal">
                @foreach($response['schema'] as $name => $schema)
                <h6 @if($loop->first) class="first" @endif>{{$name}} @if(isset($schema['type'])) <span class="tag yellow ml-1">{{$schema['type']}}</span> @endif</h6>
                @if(isset($schema['description']))
                {{$schema['description']}}
                @endif
                @endforeach
            </div>
            <div class="mb-2 detail-example"><pre><code>{{json_encode(json_decode($response['body']), JSON_PRETTY_PRINT)}}</code></pre></div>
        </div>
    </div>
    @endforeach
</div>