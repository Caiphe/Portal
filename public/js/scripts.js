document.addEventListener('DOMContentLoaded', (event) => {
    var pre = document.querySelectorAll('pre');
    hljs.configure({
        languages: ['JSON', 'JavaScript']
    });
    for (var i = pre.length - 1; i >= 0; i--) {
        hljs.highlightBlock(pre[i]);
    }
});

function closeAlert() {
    document.getElementById('alert').classList.remove('open');
    setTimeout(function(){
        document.body.removeChild(document.getElementById('alert'));
    }, 600);
}

function addAlert(type, messages) {
    var messageList = '';
    var alert = '';

    if (typeof(messages) === 'string') {
        messageList = '<li>' + messages + '</li>';
    } else {
        for (var i = 0; i < messages.length; i++) {
            messageList += '<li>' + messages[i] + '</li>';
        }
    }
    
    alert = '<div id="alert" class="' + type + '"><div class="container"><ul>' + messageList + '</ul><button class="fab blue close" onclick="closeAlert()"></button></div></div>';

    document.getElementById('header').insertAdjacentHTML('afterend', alert);

    setTimeout(function(){
        document.getElementById('alert').classList.add('open');
    }, 10);
}
