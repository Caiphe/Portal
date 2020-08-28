(function() {
    var passwordScore = 0;
    var progress = [
        'section-0',
        'section-1',
        'section-2',
        'section-3',
        'section-4'
    ];
    var inputs = document.querySelectorAll('input');

    document.getElementById('back').addEventListener('click', back);
    document.getElementById('next').addEventListener('click', next);
    document.getElementById('create-new-account').addEventListener('click', next);
    document.getElementById('password').addEventListener('input', checkPassword);
    document.getElementById('register-form').addEventListener('submit', validateSubmit);
    document.addEventListener('keydown', pressEnter);

    for (var i = inputs.length - 1; i >= 0; i--) {
        inputs[i].addEventListener('keydown', preventDefault);
    }

    function back() {
        var currentIndex = getCurrentIndex();

        document.getElementById('register-form').className = 'current-' + progress[Math.max(0, currentIndex - 1)];
    }

    function preventDefault(e) {
        if (e.which === 13) {
            e.preventDefault();
        }
    }

    function pressEnter(e) {
        if (e.which === 13) {
            next();
        }
    }

    function next() {
        var currentIndex = getCurrentIndex();
        var validated = validate(currentIndex);
        var errorElName = "";
        var errorEl = void 0;

        if (validated.preventDefault !== undefined) {
            return;
        }

        if (!validated.success) {
            for (errorElName in validated.errors) {
                errorEl = document.getElementById(errorElName);
                if (validated.errors[errorElName].length === 0) {
                    errorEl.className = 'alt';
                    continue;
                }
                errorEl.className = 'alt invalid';
                if (/password/.test(errorElName)) {
                    errorEl.nextElementSibling.nextElementSibling.innerHTML = validated.errors[errorElName].join("<br>");
                } else {
                    errorEl.nextElementSibling.innerHTML = validated.errors[errorElName].join("<br>");
                }
            }

            return;
        }

        document.getElementById('register-form').className = 'current-' + progress[currentIndex + 1];
    }

    function getCurrentIndex() {
        var currentSection = document.getElementById('register-form').className.replace('current-', '');
        return progress.indexOf(currentSection);
    }

    function validate(index) {
        return [
            validateSection0,
            validateSection1,
            validateSection2,
            validateSection3,
            validateSubmit,
        ][index]();
    }

    function validateSection0() {
        var firstName = document.getElementById('first-name').value;
        var email = document.getElementById('email').value;
        var enteredNames = document.querySelectorAll('.entered-name');
        var hasErrors = false;
        var errors = {
            'first-name': [],
            'email': []
        };

        if (firstName === '') {
            hasErrors = true;
            errors['first-name'].push('Please add your first name');
        }

        if (email === '') {
            hasErrors = true;
            errors['email'].push('Please add your email');
        }

        if (!/\w@\w/.test(email)) {
            hasErrors = true;
            errors['email'].push('Please check your email is valid');
        }

        if (hasErrors) {
            return {
                success: false,
                errors: errors
            };
        }

        for (var i = enteredNames.length - 1; i >= 0; i--) {
            enteredNames[i].textContent = firstName;
        }

        document.getElementById('last-name').focus();

        return { success: true };
    }

    function validateSection1() {
        var lastName = document.getElementById('last-name').value;
        var hasErrors = false;
        var errors = {
            'last-name': []
        };

        if (lastName === '') {
            hasErrors = true;
            errors['last-name'].push('Please add your last name');
        }

        if (hasErrors) {
            return {
                success: false,
                errors: errors
            };
        }

        document.getElementById('password').focus();

        return { success: true };
    }

    function validateSection2() {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('password-confirm').value;
        var hasErrors = false;
        var errors = {
            'password': [],
            'password-confirm': []
        };

        if (password === '') {
            hasErrors = true;
            errors['password'].push('Please add a password');
        }

        if (confirmPassword === '') {
            hasErrors = true;
            errors['password-confirm'].push('Please confirm your password');
        }

        if (confirmPassword !== password) {
            hasErrors = true;
            errors['password-confirm'].push('Your passwords do not match');
        }

        if (passwordScore !== 5) {
            hasErrors = true;
            errors['password'].push('Your password is not strong enough');
        }

        if (hasErrors) {
            return {
                success: false,
                errors: errors
            };
        }

        return { success: true };
    }

    function validateSection3() {
        return { success: true };
    }

    function validateSubmit(e) {
        var termsErrorEl = document.getElementById('terms-invalid-feedback');
        var termsAccepted = document.getElementById('terms').checked;

        if (!termsAccepted) {
            termsErrorEl.textContent = "Please accept the terms and conditions";
            termsErrorEl.className = "invalid-feedback show";
        } else {
            document.getElementById('register-form').submit();
        }

        return {success: true, preventDefault: true};
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
}());

function togglePasswordVisibility(el) {
    el.classList.toggle('password-visible');
    el.previousElementSibling.setAttribute('type', el.classList.contains('password-visible') ? "text" : "password");
}
