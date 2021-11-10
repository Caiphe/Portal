@csrf

<div class="editor-field">
    <h2>Title</h2>
    <input type="text" class="long" name="title" placeholder="Title" value="{{ $doc['title'] ?? old('title') }}">
</div>

<div class="editor-field">
    <h2>Content</h2>
    <div class="editor" data-input="body">{!! $doc['body'] ?? old('body') !!}</div>
</div>