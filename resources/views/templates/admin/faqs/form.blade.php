@csrf

<div class="editor-field">
    <h2>Category</h2>
    <select name="category_cid" id="category_cid" class="mb-1" autocomplete="off">
        <option value="" selected disabled="">Select category</option>
        @foreach($categories as $cid => $category)
        <option value="{{ $cid }}" @if(isset($faq) && $cid === $faq->category_cid || $cid === old('category_cid')) selected @endif>{{ $category }}</option>
        @endforeach
    </select>
</div>

<div class="editor-field">
    <h2>Question</h2>
    <input type="text" name="question" class="long" value="{{ $faq['question'] ?? old('question') }}">
</div>

<div class="editor-field">
    <h2>Answer</h2>
    <div class="editor" data-input="answer">{!! $faq['answer'] ?? old('answer') !!}</div>
</div>