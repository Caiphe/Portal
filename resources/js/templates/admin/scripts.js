function sync(el) {
    var xhr = new XMLHttpRequest();

    el.classList.add('syncing');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            el.classList.remove('syncing');

            if (xhr.status === 200) {
                addAlert('success', ['Syncing complete!', 'Refresh the page to see if there is anything new.']);
            } else {
                var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
                addAlert('error', (result || 'There was a problem syncing.'));
            }
        }
    };

    xhr.open("POST", bladeLookup('syncApiUrl'));
    xhr.setRequestHeader('X-CSRF-TOKEN', document.getElementsByName("csrf-token")[0].content);

    xhr.send();
}