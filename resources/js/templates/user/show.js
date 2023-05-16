var recoveryCodes = [];
var originalProfilePicture = null;

document.getElementById('profile-picture').addEventListener('change', chooseProfilePicture);

function chooseProfilePicture() {
    var files = this.files;
    var inputAccepts = this.getAttribute("accept");
    var allowedTypes = {
        "image/*": [
            "image/jpeg",
            "image/jpg",
            "image/png"
        ]
    }

    if (files.length > 1) {
        return void alert("You can only add one profile picture");
    }

    if (allowedTypes[inputAccepts] !== undefined && allowedTypes[inputAccepts].indexOf(files[0].type) !== -1) {
        originalProfilePicture = document.getElementById('profile-picture-label').style.backgroundImage;
        changeProfilePicture(files[0]);
        uploadProfilePicture(files[0]);
    } else {
        addAlert("error", "The type of image you have chosen isn't supported. Please choose a jpg or png to upload");
    }
}

function changeProfilePicture(file) {
    var reader = new FileReader();

    reader.onload = function (e) {
        document.getElementById('profile-picture-label').style.backgroundImage = "url(" + e.target.result + ")";
        document.getElementById('profile-menu-picture').style.backgroundImage = "url(" + e.target.result + ")";
    }

    reader.readAsDataURL(file);
}

function uploadProfilePicture(file) {
    var xhr = new XMLHttpRequest();

    addLoading('Uploading image...');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
            var message = [];

            removeLoading();

            if (xhr.status !== 200) {
                if (result.errors !== undefined) {
                    for (var field in result.errors) {
                        message.push(result.errors[field].join(', '));
                    }
                } else {
                    message = [result.message || 'There was an error uploading your profile picture.'];
                }

                document.getElementById('profile-picture-label').style.backgroundImage = originalProfilePicture;
                document.getElementById('profile-menu-picture').style.backgroundImage = originalProfilePicture;

                addAlert('error', message);
            }

            originalProfilePicture = null;
        }
    };

    xhr.open("POST", "/profile/update/picture");
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.getElementsByName("csrf-token")[0].content
    );

    var formData = new FormData();
    formData.append('profile', file);

    xhr.send(formData);
}

function togglePasswordVisibility(that) {
    that.parentNode.classList.toggle('password-visible');
    that.previousElementSibling.setAttribute('type', that.parentNode.classList.contains('password-visible') ? "text" : "password");
}

function copyCodes() {
    var dummy = document.createElement("textarea");
    dummy.style.position = 'absolute';
    document.body.appendChild(dummy);
    dummy.value = recoveryCodes.join("\n");
    dummy.select();
    document.execCommand("copy");
    document.body.removeChild(dummy);
    addAlert('success', 'Copied')
}

(function () {
    var passwordScore = 0;

    document.getElementById('password').addEventListener('input', checkPassword);
    document.getElementById('profile-form').addEventListener('submit', validateSubmit);
    document.getElementById('recovery-codes').addEventListener('click', showRecoveryCodes);

    var opcoRoleRequestForm =  document.getElementById('opco-role-request-form');
    if(opcoRoleRequestForm){
        opcoRoleRequestForm.addEventListener('submit', opcoRoleRequestFormSubmit);
    }

    function checkPassword() {
        var value = this.value;
        var passwordErrors = [
            'Password must be 12 characters or more.',
            'Password must have at least one special character',
            'Password must have at least one number',
            'Password must have at least one uppercase character',
            'Password must have at least one lowercase character',
        ];
        var failed = [];

        if (value.length < 12) failed.push(passwordErrors[0]);
        if (!/[!@#\$%\^\&\*\(\)_\+=\-\{\}|"':<>\?~`\.]/.test(value)) failed.push(passwordErrors[1]);
        if (!/[0-9]/.test(value)) failed.push(passwordErrors[2]);
        if (!/[A-Z]/.test(value)) failed.push(passwordErrors[3]);
        if (!/[a-z]/.test(value)) failed.push(passwordErrors[4]);

        passwordScore = passwordErrors.length - failed.length;
        document.getElementById('password-still-needs').innerHTML = failed.join('<br>');
        document.getElementById('password-strength').className = 'password-score-' + passwordScore;
    }

    function validateSubmit(ev) {
        var firstName = this.elements['first_name'].value;
        var lastName = this.elements['last_name'].value;
        var email = this.elements['email'].value;
        var password = this.elements['password'].value;
        var confirmPassword = this.elements['password_confirmation'].value;
        var errors = [];

        if (firstName === '') {
            errors.push('Please add a valid first name');
        }

        if (lastName === '') {
            errors.push('Please add a valid last name');
        }

        if (email === '' || !/\w@\w/.test(email)) {
            errors.push('Please add a valid email');
        }

        if (password !== '' && confirmPassword === '') {
            errors.push('Please confirm your password');
        } else if (confirmPassword !== password) {
            errors.push('Your passwords do not match');
        }

        if (password !== '' && passwordScore !== 5) {
            errors.push('Your password is not strong enough');
        }

        if (errors.length > 0) {
            addAlert('error', errors);
            ev.preventDefault();
            return;
        }

        addLoading('Updating your profile.');
    }

    function opcoRoleRequestFormSubmit(ev){
        ev.preventDefault();
        
        var message = this.elements['message'].value;
        var countriesCheck = this.elements['countries[]'];
        var formToken = this.elements['_token'].value;
        var errors = [];
        var countries = [];

        for(var i = 0; i < countriesCheck.length; i++){
            if(countriesCheck[i].checked){
                countries.push(countriesCheck[i].value);
            }
        }

        if(message === ''){
            errors.push('Please add a motivation message for Opco Admin role request');
        }

        if(countries.length === 0){
            errors.push('Pleasee select a country you are requesting for');
        }

        if(countries.length > 1){
            for(var i = 0; i< countriesCheck.length; i++){
                countriesCheck[i].checked = false;
            }

            errors.push('You can select only one country per request ');
        }

        if (errors.length > 0) {
            addAlert('error', errors);
            return;
        }

        var roleData = {
            message: message,
            countries: countries,
            _method: 'POST',
            _token: formToken,
        };

        var xhr = new XMLHttpRequest();

        addLoading('Sending opco admin role request...');

        xhr.open('POST', this.action);
        xhr.setRequestHeader('X-CSRF-TOKEN', formToken);
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.send(JSON.stringify(roleData));

        xhr.onload = function() {
            removeLoading();
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if (xhr.status === 200) {
                addAlert('success', ['Your opco role request has been sent. Please check your emails for the update',]);
                ev.target.reset();
                return;
               
            } else {
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

    function showRecoveryCodes() {
        addLoading('Fetching recovery codes...');

        ajax('/api/recovery-codes', 'get', null, showRecoveryCodesResponse);
    }

    function showRecoveryCodesResponse(resp) {
        recoveryCodes = resp.message;
        var codes = '<div class="recovery-codes">';

        for (var i = 0; i < recoveryCodes.length; i++) {
            codes += resp.message[i] + '<br>';
        }

        codes += '</div><button type="button" class="dark outline recovery-code-action" onclick="copyCodes()">Copy codes</button><a href="data:text/plain;charset=utf-8,' + encodeURIComponent(recoveryCodes.join("\n")) + '" download="mtn-developer-portal-recovery-codes.txt" class="button blue outline recovery-code-action" onclick="downloadCodes()">Download codes</a>';

        document.getElementById('show-recovery-codes').innerHTML = codes;

        removeLoading();
    }
}());
