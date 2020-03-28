<div class="specification-options">
    @if(!empty($description))
    <h4>Description</h4>
    @endif
    <p class="my-0">{{$description}}</p>
    
    @if(isset($request['header']))
    <h4 class="collapseable" onclick="toggleParameters(this)">@svg('chevron-right') Header parameters</h4>
    <div class="parameters">
        @foreach($request['header'] as $parameter)
        <x-products.parameter :title="$parameter['name']" :type="$parameter['type']" :required="$parameter['required'] ?? 0">{{$parameter['description']}}</x-products.parameter>
        @endforeach
    </div>
    @endif
    
    @if(isset($request['url']['query']))
    <h4 class="collapseable" onclick="toggleParameters(this)">@svg('chevron-right') Query parameters</h4>
    <div class="parameters">
        @foreach($request['url']['query'] as $parameter)
        <x-products.parameter :title="$parameter['key']" :type="$parameter['type']" :required="$parameter['required'] ?? 0">{{$parameter['description']}}</x-products.parameter>
        @endforeach
    </div>
    @endif
    
    @if(isset($request['body']))
    <h4 class="collapseable" onclick="toggleParameters(this)">@svg('chevron-right') FormData parameters</h4>
    <div class="parameters">
        @foreach($request['body']['formdata'] as $parameter)
        <x-products.parameter :title="$parameter['key']" :type="$parameter['type']" :required="$parameter['required'] ?? 0">{{$parameter['description']}}</x-products.parameter>
        @endforeach
    </div>
    @endif
</div>