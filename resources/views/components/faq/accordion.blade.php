<div class="accordion">
	<h3 class="question" onclick="toggleAnswer(this)">
		{{$question}}
        @svg('chevron-down')
    </h3>
	<div class="answer">{{ $slot }}</div>
</div>