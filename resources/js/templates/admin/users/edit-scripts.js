(function () {
    var countryAppFilter = document.getElementById('country-filter');
    var passwordEl = document.getElementById('password');
    var confirmEl = document.getElementById('password-confirm');
    var passwordScore = 0;

    passwordEl.addEventListener('input', checkPassword);
    confirmEl.addEventListener('input', checkPassword);
    document.getElementById('admin-form').addEventListener('submit', validateForm);

    if (countryAppFilter) {
        countryAppFilter.addEventListener('change', filterAppsByCountry);
    }

    function checkPassword() {
        var value = passwordEl.value;
        var passwordErrors = [
            'Password must be 12 characters or more.',
            'Password must have at least one special character',
            'Password must have at least one number',
            'Password must have at least one uppercase character',
            'Password must have at least one lowercase character',
            'Password does not match confirmed field'
        ];
        var failed = [];

        if (value.length < 12) failed.push(passwordErrors[0]);
        if (!/[!@#\$%\^\&\*\(\)_\+=\-\{\}|"':<>\?~`\.]/.test(value)) failed.push(passwordErrors[1]);
        if (!/[0-9]/.test(value)) failed.push(passwordErrors[2]);
        if (!/[A-Z]/.test(value)) failed.push(passwordErrors[3]);
        if (!/[a-z]/.test(value)) failed.push(passwordErrors[4]);
        if (passwordEl.value !== confirmEl.value) failed.push(passwordErrors[5]);

        passwordScore = passwordErrors.length - failed.length;
        document.getElementById('password-still-needs').innerHTML = failed.join('<br>');
        document.getElementById('password-strength').className = 'password-score-' + passwordScore;
    }

    function validateForm(e) {
        var spanRoles = document.getElementById('roles-tags').querySelectorAll('span');
        var spanCountry = document.getElementById('responsible_countries-tags').querySelectorAll('span');

        if(spanRoles.length < 1){
            addAlert('warning', 'Please assign at least one role to this user.');
            e.preventDefault();
            return;
        } 

        for(var i = 0; i < spanRoles.length; i++){
            if(spanRoles[i].innerHTML === 'Opco' && spanCountry.length < 1){
                addAlert('warning', 'Please select at least one country this Opco admin is responsible for.');
                e.preventDefault();
                return;
            }
        }

        if(spanCountry.length !== 0) return;

        if ((passwordEl.value !== "" || confirmEl.value !== "") && passwordScore !== 6) {
            addAlert('error', 'Please make sure your password is correct.');
            e.preventDefault();
        }
    }

    function filterAppsByCountry() {
        var userApps = document.querySelectorAll('.user-app');
        var chosenCountry = this.value;

        for (var i = 0; i < userApps.length; i++) {
            if ('all' === chosenCountry || userApps[i].dataset.country === chosenCountry) {
                userApps[i].style.display = 'table-row';
            } else {
                userApps[i].style.display = 'none';
            }
        }
    }
}());

var roleTags = document.querySelector('#roles-tags');
var btnSubmit = document.querySelectorAll('.save-button');
var roleError = document.querySelector('.role-error');


if(roleTags){
    var adminDialog = document.querySelector('.admin-removal-confirm');
    roleTags.addEventListener('click', checkAdminRemoved);
    
    function checkAdminRemoved(event){
        if(event.target.dataset.index !== undefined && event.target.dataset.index !== '1') return;
        adminDialog.classList.add('show');
        document.querySelector('.admin-removal-confirm').addEventListener('dialog-closed', adminRestore, {once:true});
    }
}

function adminRestore(e){
    addTag('1', document.getElementById('roles-select'));
    for(var i = 0; i < btnSubmit.length; i++) {
        btnSubmit[i].classList.remove('non-active');
    }
}

function closeAdminRestore(){
    adminDialog.classList.remove('show');
}

function togglePasswordVisibility(el) {
    el.classList.toggle('password-visible');
    el.previousElementSibling.setAttribute('type', el.classList.contains('password-visible') ? "text" : "password");
}

document.querySelector('#reset-2fa-btn').addEventListener('click', showConfirmTwoAF);
var confirmTwoFaModal = document.querySelector('.two-fa-modal-container');

function showConfirmTwoAF() {
    makeOwnerBtn = document.querySelector('#make-owner-btn');
    confirmTwoFaModal.classList.add('show');
    document.getElementById('confirm-twofa-form').addEventListener('submit', twoFaResetConfirmation);
}

function twoFaResetConfirmation(ev){
    ev.preventDefault();
    var form = this.elements;
    var _token = form['_token'].value;
    var user = form['user'].value;
    var fullName = document.querySelector('.header-username').innerHTML;
    var url = this.action;

    var data ={
        'user': user,
        '_token': _token
    }

    var xhr = new XMLHttpRequest();
    addLoading('Confirming users 2FA reset...');

    xhr.open('POST', url);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader("X-CSRF-TOKEN", _token);

    xhr.send(JSON.stringify(data));

    xhr.onload = function () {
        if (xhr.status === 200) {
            addAlert('success', [`2FA has been reset for ${fullName} .`], function () {
                window.location.href = '/admin/users';
                confirmTwoFaModal.classList.remove('show');
            });

        } else {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if (result.errors) {
                result.message = [];
                for (var error in result.errors) {
                    result.message.push(result.errors[error]);
                }
            }

            addAlert('error', result.message || 'Sorry there was a problem removing team. Please try again.');
        }

        removeLoading();
    };
}

document.querySelector('#user-status-btn').addEventListener('click', showConfirmStatusChange);
var statusModal = document.querySelector('.user-status-modal-container');

function showConfirmStatusChange(){
    statusModal.classList.add('show');
    document.getElementById('change-user-status-form').addEventListener('submit', confirmStatusChange);
}

function confirmStatusChange(ev){
    ev.preventDefault();
    var action = document.querySelector('#action').value;

    var form = this.elements;
    var _token = form['_token'].value;
    var user = form['user'].value;
    var url = this.action;

    var data ={
        'user': user,
        'action': action,
        '_token': _token
    }

    var xhr = new XMLHttpRequest();
    
    addLoading('Changing users status...');

    xhr.open('POST', url);
    xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader("X-CSRF-TOKEN", _token);

    xhr.send(JSON.stringify(data));

    xhr.onload = function () {
        if (xhr.status === 200) {
            addAlert('success', [`User status update`], function () {
                window.location.href = '/admin/users';
                statusModal.classList.remove('show');
            });

        } else {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if (result.errors) {
                result.message = [];
                for (var error in result.errors) {
                    result.message.push(result.errors[error]);
                }
            }

            addAlert('error', result.message || 'Sorry there was a problem removing team. Please try again.');
        }

        removeLoading();
    };
}
