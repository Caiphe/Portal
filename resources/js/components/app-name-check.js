(function () {
    let timeout = null;
    
    /* Realtime application name check */
    let applicationNameElement = document.getElementById('name');
    applicationNameElement.addEventListener('keyup', applicationNameDebounce)

    function applicationNameDebounce() {
        if (timeout) {
            clearTimeout(timeout);
            timeout = null;
        }
        timeout = setTimeout(applicationNameCheck, 512);
    }

    function applicationNameCheck() {
        let nameCheckElement = document.getElementById('nameCheck');
        let checkUri = nameCheckElement.dataset.checkUri;
        let csrfToken = nameCheckElement.dataset.token;
        let nameElement = document.getElementById('name');

        nameCheckElement.innerHTML = "Checking application name ...";
        nameCheckElement.classList.add('loading');
        nameCheckElement.classList.remove('error-check');
        nameCheckElement.classList.remove('success-check');

        if (nameElement.value === '') {
            nameCheckElement.classList.remove('loading');
            nameCheckElement.classList.remove('error-check');
            nameCheckElement.classList.remove('success-check');
            nameElement.dataset.validationState = "invalid";
            validate.showError('name_error', 'Your application name cannot be empty.');
            return;
        } else {
            validate.hideError('name_error');
        }

        let specialChrs = /[`~!@#$%^&*|+=?;:±§'",.<>\[\]\\\/]/gi;

        nameElement.value = nameElement.value.replace(/  +/g, ' ');

        if (specialChrs.test(nameElement.value)) {
            nameCheckElement.classList.remove('loading');
            nameElement.value = nameElement.value.replace(specialChrs, '');
            addAlert('warning', 'Application name cannot contain special characters.');
            return;
        }

        setTimeout(() => {
            // Check against names
            fetch(checkUri, {
                method: "POST",
                body: JSON.stringify({
                    name: nameElement.value,
                }),
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                }
            })
                .then((response) => {
                    nameCheckElement.classList.remove('error-check');
                    nameCheckElement.classList.add('success-check');
                    nameCheckElement.innerHTML = "This application name is available";
                    return response.json();
                })
                .then((json) => {
                    if (json.duplicate === true) {
                        nameCheckElement.classList.remove('success-check');
                        nameCheckElement.classList.add('error-check');
                        nameCheckElement.innerHTML = "You already have an application with this name, please use another.";
                        nameElement.dataset.validationState = "invalid";
                    } else {
                        nameCheckElement.classList.remove('loading');
                        nameCheckElement.classList.remove('error-check');
                        nameCheckElement.classList.add('success-check');
                        nameElement.dataset.validationState = "valid";
                    }
                });

        }, 500);
    }

    /* Validate Form Elements As User Interacts */
    const validate = {
        showError(elementID, errorResponseText) {
            const e = document.getElementById(elementID);
            e.innerText = errorResponseText;
            e.style.visibility = 'visible';
            e.style.display = 'block';
        },

        hideError(elementID) {
            const e = document.getElementById(elementID);
            e.style.visibility = 'hidden';
            e.style.display = 'none';
        },

        /**
         * This checks if the application name has a valid state. There is other code which
         * checks and sets validity, but this one manages the programmatic check where needed.
         * @param elementID
         * @return boolean
         */
        checkAppName(elementID) {
            const e = document.getElementById(elementID);
            const eValidationState = e.dataset.validationState;
            return eValidationState === "valid";
        },

        /**
         * This very simply checks if a field has been completed, because it is required.
         * The value itself is not checked.
         * @param elementID
         * @return boolean
         */
        required(elementID) {
            const e = document.getElementById(elementID);
            return e.value !== '';
        },

        /**
         * This checks that the given form element array of checkboxes has a specific number of checkboxes
         * selected.
         */
        checked(inputName, min) {
            const checkboxes = document.querySelectorAll('input[name="' + inputName + '[]"]:checked');
            return checkboxes.length >= min;
        },
    }
}());
