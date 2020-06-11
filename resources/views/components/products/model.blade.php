@foreach($schemas as $name => $schema)
@php
    $hasChildren = !isset($schema['type']) || gettype($schema['type']) === 'array';
    $h6Class = $hasChildren ? 'has-children' : 'no-children';
    if($loop->first){
        $h6Class .= " first";
    }
@endphp

<div class="schema">
    <h6 class="{{$h6Class}}" @if($hasChildren) onclick="toggleParent(this)" @endif>
        {{$name}}
        @if(!$hasChildren)
            <span class="tag yellow ml-1">{{$schema['type']}}</span>
        @else
            @svg('minus')@svg('plus')
        @endif
    </h6>

    @if(!$hasChildren && isset($schema['description']))
    {{$schema['description']}}
    @endif

    @if($hasChildren)
    <x-products.model :schemas="$schema" />
    @endif
</div>
@endforeach