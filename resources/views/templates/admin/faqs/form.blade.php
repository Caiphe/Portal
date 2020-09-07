@csrf

<select name="category_cid" id="category_cid" class="mb-1" autocomplete="off">
    <option value="" selected disabled="">Select category</option>
    @foreach($categories as $cid => $category)
    <option value="{{ $cid }}" @if(isset($faq) && $cid === $faq->category_cid) selected @endif>{{ $category }}</option>
    @endforeach
</select>
<br>
<input type="text" name="question" value="{{ $faq['question'] ?? '' }}">
<input id="answer" type="hidden" name="answer" value="{{ $faq['answer'] ?? '' }}">
<trix-editor input="answer"></trix-editor>