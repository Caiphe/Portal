function ajax(url, type, data, cb) {
    let xhr = new XMLHttpRequest();

    xhr.open(type, url);
    xhr.setRequestHeader("X-CSRF-TOKEN", document.getElementsByName("csrf-token")[0].content);

    if (document.cookie.indexOf("XSRF-TOKEN") !== -1) {
        xhr.setRequestHeader("X-XSRF-TOKEN", getCookie("XSRF-TOKEN"));
    }
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader("Content-type", "application/json; charset=utf-8");

    xhr.send(JSON.stringify(data || []));

    xhr.onload = function() {
        if (/^2/.test(xhr.status)) {
            result = getResponseText(xhr, "Success");

            cb &&
                cb({
                    success: true,
                    message: result,
                    code: xhr.status,
                });
        } else if (/^3/.test(xhr.status)) {
            result = getResponseText(xhr, "Request tried to redirect");

            cb &&
                cb({
                    success: true,
                    message: result,
                    code: xhr.status,
                });
        } else {
            result = getResponseText(xhr, "Unknown error");

            cb &&
                cb({
                    success: false,
                    message: result.message,
                    code: xhr.status,
                });
        }
    };
}

function getCookie(name) {
    var v = document.cookie.match("(^|;) ?" + name + "=([^;]*)(;|$)");
    return v ? v[2] : null;
}

function getResponseText(xhr, msg) {
    response = xhr.responseText;

    if (/content-type: application\/json/.test(xhr.getAllResponseHeaders())) {
        response = JSON.parse(response);
    }

    if (typeof response === 'string' && response !== '') {
        return {
            message: response
        }
    }

    return response || { message: msg };
}
