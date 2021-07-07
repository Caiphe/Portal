<div class="specification-options">
    @if(isset($request['header']))
    <h4>Header parameters</h4>
    <div class="parameters">
        @foreach($request['header'] as $parameter)
        <x-products.parameter :title="$parameter['name']" :type="$parameter['type']" :required="$parameter['required'] ?? 0">{{$parameter['description']}}</x-products.parameter>
        @endforeach
    </div>
    @endif
    
    @if(isset($request['auth']) && !empty($request['auth']))
    <h4>Auth parameters</h4>
    <div class="parameters">
        <x-products.parameter :title="$request['auth']['type']" :type="$request['auth'][$request['auth']['type']][0]['value']" :required="0"></x-products.parameter>
    </div>
    @endif
    
    @if(isset($request['url']['query']))
    <h4>Query parameters</h4>
    <div class="parameters">
        @foreach($request['url']['query'] as $parameter)
        <x-products.parameter :title="$parameter['key']" :type="$parameter['type']" :required="$parameter['required'] ?? 0" state="open">{{$parameter['description']}}</x-products.parameter>
        @endforeach
    </div>
    @endif
    
    @if(isset($request['body']))
    <h4>FormData parameters</h4>
    <div class="parameters">
        @foreach($request['body']['formdata'] as $parameter)
        <x-products.parameter :title="$parameter['key']" :type="$parameter['type']" :required="$parameter['required'] ?? 0" state="open">{!! $parameter['description'] !!}</x-products.parameter>
        @endforeach
    </div>
    @endif
</div>