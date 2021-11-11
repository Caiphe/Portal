var editors = [];

(function () {
    var editerEls = document.querySelectorAll('.editor');

    for (var i = 0; i < editerEls.length; i++) {
        makeEditor(editerEls[i]);
    }
}());

function makeEditor(els) {
    ClassicEditor.create(els, {
        simpleUpload: {
            uploadUrl: '/api/admin/editor/upload',
            headers: {
                'X-CSRF-TOKEN': document.getElementsByName("csrf-token")[0].content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        }
    }).then(makeEditorInit)
        .catch(makeEditorCatch);
}

function makeEditorInit(editor) {
    var el = editor.sourceElement;

    el.insertAdjacentElement('afterend', Object.assign(document.createElement('input'), {
        type: 'hidden',
        name: el.dataset.name ?? el.dataset.input,
        id: 'editor-' + el.dataset.input,
        className: el.dataset.class || '',
        value: editor.getData()
    }))

    editors.push(editor);

    el.closest('form').addEventListener('submit', addContents); 
}

function makeEditorCatch(error) {
    console.log(error);
}

function addContents() {
    var editorInput = null;

    for (var i = 0; i < editors.length; i++) {
        editorInput = document.getElementById('editor-' + editors[i].sourceElement.dataset.input);

        if(!editorInput) continue;

        editorInput.value = editors[i].getData();
    }
}