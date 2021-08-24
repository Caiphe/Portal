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
    changeProfilePicture(files[0]);
    uploadProfilePicture(files[0]);
    } else {
    addAlert("error", "The type of image you have chosen isn't supported. Please choose a jpg or png to upload");
    }
}

function changeProfilePicture(file) {
    var reader = new FileReader();

    reader.onload = function(e) {
        document.getElementById('profile-picture-label').style.backgroundImage = "url(" + e.target.result + ")";
        document.getElementById('profile-menu-picture').style.backgroundImage = "url(" + e.target.result + ")";
    }

    reader.readAsDataURL(file);
}

function uploadProfilePicture(file) {
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
            var message = [];
            if (xhr.status !== 200) {
                if (result.errors !== undefined) {
                    for (var field in result.errors) {
                        message.push(result.errors[field].join(', '));
                    }
                } else {
                    message = [result.message || 'There was an error uploading your profile picture.'];
                }
                
                addAlert('error', message);
            }
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

(function() {
    var passwordScore = 0;

    document.getElementById('password').addEventListener('input', checkPassword);
    document.getElementById('profile-form').addEventListener('submit', validateSubmit);

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
}());
