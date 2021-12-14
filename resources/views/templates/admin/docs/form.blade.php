@csrf

<div class="editor-field">
    <h2>Content</h2>

    <label class="editor-field-label">
        <h3>Title</h3>
        <input type="text" class="long" name="title" placeholder="Title" value="{{ $doc['title'] ?? old('title') }}">
    </label>

    <label class="editor-field-label">
        <h3>Content</h3>
        <div class="editor" data-input="body">{!! $doc['body'] ?? old('body') !!}</div>
    </label>
</div>
