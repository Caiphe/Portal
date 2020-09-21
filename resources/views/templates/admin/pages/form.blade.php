@csrf

<div class="editor-field">
    <h2>Title</h2>
    <input type="text" class="long" name="title" placeholder="Title" value="{{ $page['title'] ?? '' }}">
</div>

<div class="editor-field">
    <h2>Content</h2>
    <input id="body" type="hidden" name="body" value="{{ $page['body'] ?? '' }}">
    <trix-editor input="body"></trix-editor>
</div>