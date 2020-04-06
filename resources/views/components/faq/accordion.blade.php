<div id="{{$id}}" class="accordion">
	<h3 class="question">
		{{$question}}
        @svg('chevron-down')
    </h3>
	<div class="answer">{{ $slot }}</div>
</div>