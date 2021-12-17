@csrf

<div class="editor-field">
    <h2>Content</h2>

    <label class="editor-field-label">
        <h3>Category</h3>
        <select name="category_cid" id="category_cid" class="mb-1" autocomplete="off">
            <option value="" selected disabled="">Select category</option>
            @foreach($categories as $cid => $category)
            <option value="{{ $cid }}" @if(isset($faq) && $cid === $faq->category_cid || $cid === old('category_cid')) selected @endif>{{ $category }}</option>
            @endforeach
        </select>
    </label>

    <label class="editor-field-label">
        <h3>Question</h3>
        <input type="text" name="question" class="long" value="{{ $faq['question'] ?? old('question') }}">
    </label>

    <div class="editor-field-label">
        <h3>Answer</h3>
        <div class="editor" data-input="answer">{!! $faq['answer'] ?? old('answer') !!}</div>
    </div>

    <button class="button outline blue save-button">Apply changes</button>
</div>
