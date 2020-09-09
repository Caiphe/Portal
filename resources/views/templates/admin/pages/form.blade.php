@csrf

<input type="text" name="title" placeholder="Title" value="{{ $page['title'] ?? '' }}">
<input id="body" type="hidden" name="body" value="{{ $page['body'] ?? '' }}">
<trix-editor input="body"></trix-editor>