function closeAlert() {
    document.getElementById('alert').classList.remove('open');
    setTimeout(function() {
        document.body.removeChild(document.getElementById('alert'));
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
