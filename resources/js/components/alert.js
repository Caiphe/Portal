function closeAlert() {
    var alert = document.getElementById('alert');
    
    if (!alert) return;
    
    alert.classList.remove('open');
    setTimeout(function() {
        document.body.removeChild(alert);
        alert = null;
    }, 600);
}

function addAlert(type, messages, cb) {
    var messageList = '';
    var alert = '';
    var time = type.toLowerCase() === 'error' ? 10000 : 4000;

    if (typeof(messages) === 'string') {
        messageList = '<li>' + messages + '</li>';
    } else {
        for (var i = 0; i < messages.length; i++) {
            messageList += '<li>' + messages[i] + '</li>';
        }
    }

    alert = '<div id="alert" class="' + type + '"><ul>' + messageList + '</ul><button onclick="closeAlert()">&times;</button></div>';

    document.body.insertAdjacentHTML('beforeend', alert);

    setTimeout(function() {
        document.getElementById('alert').classList.add('open');
        setTimeout((cb || closeAlert), time);
    }, 10);
}

if (document.getElementById('alert')) {
    setTimeout(closeAlert, 4000);
}

function addLoading(msg) {
    var loading = '<div id="alert-loading">' + (msg || 'Loading...') + ' <svg xmlns="http://www.w3.org/2000/svg" stroke="#1a1a1a" height="42" width="42" viewbox="0,0,42,42"><g transform="translate(2 2)"><g stroke-width="6" transform="translate(1 1)" fill-rule="evenodd" fill="none"><circle r="18" cy="18" cx="18" stroke-opacity="0.2" stroke="#1a1a1a"/><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform repeatCount="indefinite" dur="1s" to="360 18 18" from="0 18 18" type="rotate" attributeName="transform"/></path></g></g></svg></div>';

    document.body.insertAdjacentHTML('beforeend', loading);

    setTimeout(function() {
        document.getElementById('alert-loading').classList.add('open');
    }, 10);

}

function removeLoading() {
    var loading = document.getElementById('alert-loading');
    
    if (!loading) return;

    loading.classList.remove('open');

    setTimeout(function() {
        document.body.removeChild(loading)
    }, 600);
}
