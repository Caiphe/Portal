var viewMotivationBtns = document.getElementsByClassName("view-motivation-button");
var denyBtns = document.getElementsByClassName("deny-btn");

for (var i = 0; i < denyBtns.length; i++) {
    denyBtns[i].addEventListener("click", showDenyPopup);
}

for (var i = 0; i < viewMotivationBtns.length; i++) {
    viewMotivationBtns[i].addEventListener("click", toggleTask);
}

function toggleTask() {
    this.classList.toggle('rotate-svg');
    this.closest('.single-task').classList.toggle('active');
}

function showDenyPopup() {
    this.nextElementSibling.classList.add('show');
}

var approveForms = document.querySelectorAll('.approval-form');
var denyForms = document.querySelectorAll('.deny-form');

for(var i = 0; i < approveForms.length; i++){
    approveForms[i].addEventListener('submit', submitRoleApproval);
}

for(var i = 0; i < denyForms.length; i++){
    denyForms[i].addEventListener('submit', submitRoleDenial);
}

function submitRoleApproval(event)
{ 
    event.preventDefault();

    var request_id = this.elements['request_id'].value;
    var formToken = this.elements['_token'].value;

    var roleData = {
        message: "Approved",
        request_id: request_id,
        _method: 'POST',
        _token: formToken,
    };

    var xhr = new XMLHttpRequest();

    addLoading('Approving opco admin request...');

    xhr.open('POST', this.action);
    xhr.setRequestHeader('X-CSRF-TOKEN', formToken);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.send(JSON.stringify(roleData));

    xhr.onload = function() {
        removeLoading();
        if (xhr.status === 200) {
            addAlert('success', ['You have approved an opco admin role request',], function(){
                location.reload();
            });
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

function submitRoleDenial(event){
    event.preventDefault();
    var request_id = this.elements['request_id'].value;
    var formToken = this.elements['_token'].value;
    var message = this.elements['message'].value;
    var errors = [];
    
    if(message === ''){
        errors.push('Please leave a reason for deniyng the request');
    }

    if (errors.length > 0) {
        addAlert('error', errors);
        return;
    }

    var roleData = {
        message: message,
        request_id: request_id,
        _method: 'POST',
        _token: formToken,
    };

    var xhr = new XMLHttpRequest();

    addLoading('Denying opco admin role request...');

    xhr.open('POST', this.action);
    xhr.setRequestHeader('X-CSRF-TOKEN', formToken);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.send(JSON.stringify(roleData));

    xhr.onload = function() {
        removeLoading();
        event.target.reset();

        if (xhr.status === 200) {
            addAlert('success', ['You have denied opco admin role request',], function(){
                    location.reload();
            });
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