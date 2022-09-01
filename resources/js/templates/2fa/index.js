var checkConfirm = document.getElementById('confirm');
var resetTwoaf = document.getElementById('reset-twofa');
var resetBtn = document.getElementById('reset-btn');
var form2fa = document.getElementById('form-2fa');
var resetForm = document.getElementById('reset-form');
var resetContainer = document.getElementById('reset-container');
var completeTwofaContainer = document.getElementById('complete-request');

checkConfirm.addEventListener('click', toggleButton);
function toggleButton(){
    if(this.checked){
        resetBtn.classList.remove('non-active');
    }else{
        resetBtn.classList.add('non-active');
    }
}

resetTwoaf.addEventListener('click', resetTwoafForm);
function resetTwoafForm(){
    this.classList.add('hide');
    form2fa.classList.add('hide');
    resetContainer.classList.add('show');
}

resetForm.addEventListener('submit', submitResetTwofa);
function submitResetTwofa(ev){
    ev.preventDefault();
   
    var formToken = this.elements['_token'].value;
    var user = this.elements['user'].value;

    var twofaData = {
        user: user,
        _method: 'POST',
        _token: formToken
    };

    var xhr = new XMLHttpRequest();

    addLoading('sending a 2fa reset request ...');

    xhr.open("POST", this.action, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', formToken);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.send(JSON.stringify(twofaData));

    xhr.onload = function() {
        removeLoading();
        if (xhr.status === 200) {
            resetContainer.classList.remove('show');
            completeTwofaContainer.classList.add('show');
            addAlert('success', [`Authentification reset request has been completed.`]);
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
