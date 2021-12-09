function sync(el) {
    el.classList.add('syncing');

    syncProductsThenApps(function () {
        el.classList.remove('syncing');
        el = null;
    })
}

function syncProductsThenApps(cb) {
    syncProducts(function(){
        window.setTimeout(syncApps.bind(null, cb), 256);
    });
}

function syncApps(cb) {
    var xhr = new XMLHttpRequest();

    addLoading('Syncing apps');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            removeLoading();
            cb && cb();

            if (xhr.status === 200) {
                addAlert('success', ['Syncing complete!', 'Refresh the page to see if there is anything new.']);
            } else {
                var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
                addAlert('error', (result || 'There was a problem syncing.'));
            }
        }
    };

    xhr.open("POST", bladeLookupAdmin('syncAppApiUrl'));
    xhr.setRequestHeader('X-CSRF-TOKEN', document.getElementsByName("csrf-token")[0].content);

    xhr.send();
}

function syncProducts(cb) {
    var xhr = new XMLHttpRequest();

    addLoading('Syncing products');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            removeLoading();

            if (xhr.status === 200) {
                if (cb !== undefined) {
                    cb();
                } else {
                    addAlert('success', ['Syncing complete!', 'Refresh the page to see if there is anything new.']);
                }
            } else {
                var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
                addAlert('error', (result || 'There was a problem syncing.'));
            }
        }
    };

    xhr.open("POST", bladeLookupAdmin('syncProductApiUrl'));
    xhr.setRequestHeader('X-CSRF-TOKEN', document.getElementsByName("csrf-token")[0].content);

    xhr.send();
}
