(function() {
    var els = document.querySelectorAll('.ajaxify, .page-link');

    for (var i = els.length - 1; i >= 0; i--) {
        els[i].classList.add('ajaxified');
        els[i].addEventListener((els[i].nodeName === 'FORM' ? 'submit' : 'click'), handleForm);
    }

    function handleForm(ev) {
        var xhr = new XMLHttpRequest();
        var formData = new FormData();
        var el = this;
        var method = (this.method || 'GET').toUpperCase();
        var url = el.action || el.href;
        var isPager = el.classList.contains('page-link');

        ev.preventDefault();

        if (el.dataset.confirm !== undefined && !confirm(el.dataset.confirm)) return;

        addLoading();

        xhr.onreadystatechange = function() {
            var result = null;
            var func = null;
            var args = null;

            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (/^text\/html/.test(xhr.getResponseHeader("Content-Type"))) {
                    result = xhr.responseText;
                } else {
                    result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
                }

                if (xhr.status === 200) {
                    if (el.dataset.func !== undefined) {
                        func = el.dataset.func.replace(/\(.*/, '');
                        args = el.dataset.func.match(/\(([^\)]*)\)/)[1];

                        window[func](args);
                    }

                    if (el.dataset.replace !== undefined || isPager) {
                        document.querySelector((el.dataset.replace || '#table-data')).innerHTML = result;
                        resetAjaxify();
                        updateUrl(url);
                    } else {
                        addAlert('success', (result.body || "Success"));
                    }

                    if(typeof ajaxifyComplete !== 'undefined') ajaxifyComplete();

                } else {
                    addAlert('error', (result.body || "Sorry there was an unexpected error."));
                }

                removeLoading();
            }
        };

        if (el.elements !== undefined && method === "GET") {
            url += '?true=1';
            for (var i = el.elements.length - 1; i >= 0; i--) {
                if (el.elements[i].name === "") continue;

                url += '&' + el.elements[i].name + '=' + el.elements[i].value;
            }
        } else if(el.elements !== undefined) {
            for (var i = el.elements.length - 1; i >= 0; i--) {
                if (el.elements[i].name === "") continue;

                formData.append(el.elements[i].name, el.elements[i].value);
            }
        }

        xhr.open(method, url);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader(
            "X-CSRF-TOKEN",
            document.getElementsByName("csrf-token")[0].content
        );

        xhr.send(formData);
    }

    function resetAjaxify() {
        var els = document.querySelectorAll('.ajaxify:not(.ajaxified), .page-link');

        for (var i = els.length - 1; i >= 0; i--) {
            els[i].addEventListener('click', handleForm);
        }
    }

    function updateUrl(url) {
        history.pushState(null, null, url.replace(/.*\/|true=1\&/g, ''));
    }
}());
