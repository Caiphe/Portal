function init() {
    var mobileActions = document.querySelectorAll('.mobile-action');

    if(mobileActions) {
        for (var i = mobileActions.length - 1; i >= 0; i--) {
            mobileActions[i].addEventListener('click', toggleMobileAction);
        }
    }

    function toggleMobileAction() {
        this.parentNode.parentNode.classList.toggle('show-actions');
    }
}

init();
ajaxifyComplete = init;

document.getElementById('menu-button').addEventListener('click', toggleMenu);
document.getElementById('hide-menu').addEventListener('click', toggleMenu);
document.getElementById('search-form-toggle').addEventListener('click', toggleFilter);

function toggleMenu() {
    document.getElementById('sidebar').classList.toggle('show');
}

function toggleFilter() {
    document.getElementById('search-form').classList.toggle('show');
}

function syncProductsThenApps() {
    syncProducts(syncApps);
}

function syncApps() {
    var xhr = new XMLHttpRequest();

    addLoading('Syncing apps...');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            removeLoading();

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
                    window.setTimeout(cb, 264);
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
