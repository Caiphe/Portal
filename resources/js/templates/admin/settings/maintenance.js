document.getElementById('maintenance-form').addEventListener('submit', createNotification);

function createNotification(e) {
    e.preventDefault();
    var formToken = this.elements['_token'].value;
    var enabled = this.elements['maintenance'].value;

    var data = {
        _method: 'POST',
        _token: formToken,
        message: this.elements['message'].value,
        enabled: enabled,
    };

    var xhr = new XMLHttpRequest();
    addLoading('updating notification banner ...');

    xhr.open('POST', this.action);
    xhr.setRequestHeader('X-CSRF-TOKEN', formToken);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send(JSON.stringify(data));

    xhr.onload = function() {
        removeLoading();

        if (xhr.status === 200) {
            if(enabled === 'disabled'){
                addAlert('warning', [`Notification banner has been disabled.`]);
            }else{
                addAlert('success', [`Notification banner has been enabled.`]);
            }

            return;

        } else {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if(result.errors) {
                result.message = [];
                for(var error in result.errors){
                    result.message.push(result.errors[error]);
                }
            }

            addAlert('error', result.message || 'Sorry there was a problem with your opco admin request. Please try again.');
        }
    };
}
