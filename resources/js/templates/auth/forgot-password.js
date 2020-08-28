(function(){
    var passwordScore = 0;
    document.getElementById('password').addEventListener('input', checkPassword);
    document.getElementById('forgot-password-form').addEventListener('submit', validate);

    function checkPassword(returnFailed) {
        var value = document.getElementById('password').value;
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

        if(returnFailed === true) {
            return failed;
        }

        document.getElementById('password-still-needs').innerHTML = failed.join('<br>');
        document.getElementById('password-strength').className = 'password-score-' + passwordScore;
    }

    function validate(ev) {
        var emailVal = document.getElementById('email').value;
        var passwordVal = document.getElementById('password').value;
        var confirmVal = document.getElementById('password-confirm').value;
        var errors = [];

        if(emailVal === "" || !/@/.test(emailVal)){
            errors.push('Please enter a valid email');
        }

        if (passwordScore !== 5) {
            errors = errors.concat(checkPassword(true));
        }

        if (confirmVal !== passwordVal) {
            errors.push("Confirm password doesn't match");
        }

        if(errors.length > 0){
            ev.preventDefault();

            document.getElementById('password-still-needs').innerHTML = errors.join('<br>');
        }
    }
}());