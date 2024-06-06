document.getElementById('maintenance-form').addEventListener('submit', createNotification);

function createNotification(e) {
    e.preventDefault();

    var formToken = this.elements['_token'].value;

    var data = {
        _method: 'POST',
        _token: formToken,
        message: this.elements['message'].value,
        enabled: this.elements['maintenance'].value,
    };

    var xhr = new XMLHttpRequest();

    addLoading('creating a new banner notification...');

    xhr.open('POST', this.action);
    xhr.setRequestHeader('X-CSRF-TOKEN', formToken);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send(JSON.stringify(data));

    xhr.onload = function() {
        removeLoading();

        if (xhr.status === 200) {
            addAlert('success', [`Banner notification created successfully.`]);
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
