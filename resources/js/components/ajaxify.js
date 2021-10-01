(function () {
    var els = document.querySelectorAll('.ajaxify, .page-link');
    var ajaxedHtml = [];

    window.onpopstate = function(event) {
        var params = event.state === null ? {} : event.state.params;

        if (typeof ajaxifyOnPopState !== 'undefined') ajaxifyOnPopState(params);

        ajaxedHtml.pop();
        document.getElementById('table-data').innerHTML = ajaxedHtml[ajaxedHtml.length - 1];
    }

    for (var i = els.length - 1; i >= 0; i--) {
        els[i].classList.add('ajaxified');
        els[i].addEventListener((els[i].nodeName === 'FORM' ? 'submit' : 'click'), handleForm);
    }

    function handleForm(ev) {
        ev.preventDefault();

        var xhr = new XMLHttpRequest();
        var formData = new FormData();
        var el = this;
        var method = (this.method || 'GET').toUpperCase();
        var url = el.action || el.href;
        var isPager = el.classList.contains('page-link');

        if (el.dataset.confirm !== undefined && !confirm(el.dataset.confirm)) return;

        if(ajaxedHtml.length === 0){
            ajaxedHtml.push(document.querySelector((el.dataset.replace || '#table-data')).innerHTML);
        }

        addLoading();

        xhr.onreadystatechange = function () {
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
                        ajaxedHtml.push(result || '');
                        resetAjaxify();
                        updateUrl(url);
                    } else {
                        addAlert('success', (result.body || "Success"));
                    }

                    if (typeof ajaxifyComplete !== 'undefined') ajaxifyComplete();

                } else {
                    addAlert('error', (result.body || "Sorry there was an unexpected error."));
                }

                removeLoading();
            }
        };

        if (el.elements !== undefined && method === "GET") {
            url += '?true=1';
            value = "";
            for (var i = el.elements.length - 1; i >= 0; i--) {
                if (el.elements[i].name === "" || el.elements[i].value === "") continue;

                if (el.elements[i].multiple) {
                    url += getMultiselectValues(el.elements[i]);
                } else {
                    url += '&' + el.elements[i].name + '=' + encodeURIComponent(el.elements[i].value);
                }

            }
            url = url.replace(/true=1&?|\?true=1$/, '');
        } else if (el.elements !== undefined) {
            for (var i = el.elements.length - 1; i >= 0; i--) {
                if (el.elements[i].name === "" || el.elements[i].value === "") continue;

                formData.append(el.elements[i].name, encodeURIComponent(el.elements[i].value));
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
        var urlReplace = url.replace(/.*\?/g, '?').replace(/true=1\&/, '');
        var params = new URLSearchParams(urlReplace);
        params = Object.fromEntries(params.entries());
        history.pushState({ query: urlReplace, params: params }, null, urlReplace);
    }

    function getMultiselectValues(multi) {
        var selected = '';
        for (var i = multi.options.length - 1; i >= 0; i--) {
            if (multi.options[i].selected) {
                selected += '&' + multi.name + '=' + multi.options[i].value;
            };
        }
        return selected;
    }
}());
