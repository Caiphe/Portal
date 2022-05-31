(function () {
    var countryAppFilter = document.getElementById('country-filter');
    var passwordEl = document.getElementById('password');
    var confirmEl = document.getElementById('password-confirm');
    var btnSubmit = document.querySelectorAll('.save-button');
    var passwordScore = 0;

    for(var i = 0; i < btnSubmit.length; i++) {
        btnSubmit[i].addEventListener('click', checkOpcoRole);
    }

    function checkOpcoRole(event){
        var spanRoles = document.getElementById('roles-tags').querySelectorAll('span');
        var spanCountry = document.getElementById('responsible_countries-tags').querySelectorAll('span');

        if(spanCountry.length !== 0) return;

        for(var i = 0; i < spanRoles.length; i++){
            if(spanRoles[i].innerHTML === 'Opco'){
                addAlert('warning', 'Please select at least one country this Opco admin is responsible for.');
                event.preventDefault();
                return;
            }
        }
    }

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

var spanRoles = document.getElementById('roles-tags').querySelectorAll('span');
var adminDialog = document.querySelector('.admin-removal-confirm');

for(var i = 0; i < spanRoles.length; i++){
    if(spanRoles[i].innerHTML === 'Admin'){
        spanRoles[i].addEventListener('click', checkAdminRemoved);
    }
}

function checkAdminRemoved(event)
{
    event.preventDefault();
    adminDialog.classList.add('show');
    return;
}

function adminRestore(){
    addTag('1', document.getElementById('roles-select'));
    closeAdminRestore();
}

function closeAdminRestore(){
    adminDialog.classList.remove('show');
}

function togglePasswordVisibility(el) {
    el.classList.toggle('password-visible');
    el.previousElementSibling.setAttribute('type', el.classList.contains('password-visible') ? "text" : "password");
}
