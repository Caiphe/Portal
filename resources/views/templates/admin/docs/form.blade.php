@csrf

<input type="text" name="title" placeholder="Title" value="{{ $doc['title'] ?? '' }}">
<input id="body" type="hidden" name="body" value="{{ $doc['body'] ?? '' }}">
<trix-editor input="body"></trix-editor>