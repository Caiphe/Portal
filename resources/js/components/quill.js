var editors = [];

(function () {
    var quills = document.querySelectorAll('.editor');

    for (var i = 0; i < quills.length; i++) {
        makeEditor(quills[i]);
    }
}());

function makeEditor(quill) {
    var editor = new Quill(quill, {
        modules: {
            toolbar: [
                [{ 'header': [2, 3] }],

                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],

                [{ 'list': 'ordered' }, { 'list': 'bullet' }],

                ['link', 'image'],

                ['clean']
            ]
        },
        theme: 'snow'
    });

    quill.insertAdjacentElement('afterend', Object.assign(document.createElement('input'), {
        type: 'hidden',
        name: quill.dataset.name ?? quill.dataset.input,
        id: 'quill-' + quill.dataset.input,
        className: 'quill-editor ' + quill.dataset.class || '',
        value: editor.root.innerHTML
    }))

    editors.push(editor);

    quill.closest('form').addEventListener('submit', addContents);
}

function addContents() {
    var editorInput = null;

    for (var i = 0; i < editors.length; i++) {
        editorInput = document.getElementById('quill-' + editors[i].container.dataset.input);

        if(!editorInput) continue;

        editorInput.value = editors[i].getText() === '' ? '' : editors[i].root.innerHTML;
    }
}