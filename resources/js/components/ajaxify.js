(function() {
    var forms = document.querySelectorAll('.ajaxify');

    for (var i = forms.length - 1; i >= 0; i--) {
        forms[i].addEventListener('submit', handleForm);
    }

    function handleForm(ev) {
        var xhr = new XMLHttpRequest();
        var formData = new FormData();
        var form = this;

        ev.preventDefault();

        if(form.dataset.confirm !== undefined && !confirm(form.dataset.confirm)) return;

        xhr.onreadystatechange = function() {
            var result = null;
            var func = null;
            var args = null;

            if (xhr.readyState === XMLHttpRequest.DONE) {
                result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

                if (xhr.status === 200) {
                    if(form.dataset.func !== undefined){
                        func = form.dataset.func.replace(/\(.*/, '');
                        args = form.dataset.func.match(/\(([^\)]*)\)/)[1];

                        window[func](args);
                    }
                    addAlert('success', result.body || "Success");
                } else {
                    addAlert('error', result.body || "Sorry there was an unexpected error.");
                }
            }
        };

        xhr.open(form.method, form.action);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader(
            "X-CSRF-TOKEN",
            document.getElementsByName("csrf-token")[0].content
        );

        for (var i = form.elements.length - 1; i >= 0; i--) {
            if (form.elements[i].name === "") continue;

            formData.append(form.elements[i].name, form.elements[i].value);
        }

        xhr.send(formData);
    }
}());

function ajaxify(el, cb) {

}
