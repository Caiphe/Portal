<div class="parameter">
    <h5 onclick="toggleParent(this)">
        @svg('chevron-right')
        {{ $title }}
        <span class="tag yellow ml-1">{{ $type }}</span>
        @if($required === true || $required === 1)
        <span class="tag error">required</span>
        @endif
    </h5>
    {!! $slot !!}
</div>