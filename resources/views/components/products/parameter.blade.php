<div class="parameter">
    <h5>
        {{$title}}
        <span class="tag yellow ml-1">{{$type}}</span>
        @if($required === '1')
        <span class="tag error ml-0">required</span>
        @endif
    </h5>
    {{$slot}}
</div>