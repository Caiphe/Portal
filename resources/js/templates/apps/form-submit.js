(function () {
    /* Variables */
    let timeout = null;
    let inputData = document.querySelector('.selected-data');
    let select_wrap = document.querySelector('.select_wrap');
    let select_ul = document.querySelectorAll('.select_ul li');

    /* Cancel Button */
    let cancelButtonElement = document.getElementById('cancel');
    cancelButtonElement.addEventListener('click', handleCancelButtonClickEvent)

    function handleCancelButtonClickEvent(event) {
        event.preventDefault();
        if (window.confirm('Are you sure you want to leave this page?') === true) {
            window.location.replace(this.dataset.backUrl);
        }
    }

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

        nameCheckElement.querySelector('p')
            .innerText = 'Checking application name...';
        nameCheckElement.querySelector('img')
            .src = '/images/icons/loading.svg'
        nameCheckElement.classList.remove('warning');
        nameCheckElement.classList.add('show-flex');

        if (nameElement.value === '') {
            nameCheckElement.classList.remove('show-flex');
            nameElement.dataset.validationState = "invalid";
            validate.showError('name_error', 'Your application name cannot be empty.');
            return;
        } else {
            validate.hideError('name_error');
        }

        let specialChrs = /[`~!@#$%^&*|+=?;:±§'",.<>\[\]\\\/]/gi;

        nameElement.value = nameElement.value.replace(/  +/g, ' ');

        if (specialChrs.test(nameElement.value)) {
            nameCheckElement.classList.remove('show-flex');
            nameElement.value = nameElement.value.replace(specialChrs, '');
            addAlert('warning', 'Application name cannot contain special characters.');
            return;
        }

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
                return response.json();
            })
            .then((json) => {
                if (json.duplicate === true) {
                    nameCheckElement.classList.add('warning');
                    nameCheckElement.querySelector('p')
                        .innerText = 'You already have an application with this name, please use another.';
                    nameCheckElement.querySelector('img')
                        .src = '/images/icons/error-cross.png'
                    nameElement.dataset.validationState = "invalid";
                } else {
                    nameCheckElement.classList.remove('show-flex');
                    nameElement.dataset.validationState = "valid";
                }
            });
    }

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

    /* Complete Button */
    let completeButtonElement = document.getElementById('complete');
    completeButtonElement.addEventListener('click', handleCompleteButtonClickEvent);

    function handleCompleteButtonClickEvent() {
        let formError = false;

        // Validate the application name
        // if (!validate.checkAppName('name')) {
        //     validate.showError('name_error', 'The application name is not valid.');
        //     formError = true;
        // }

        // Validate the required fields
        if (!validate.required('entity_name')) {
            validate.showError('entity_name_error', 'Entity name is required.')
            formError = true;
        } else {
            validate.hideError('entity_name_error');
        }

        if (!validate.required('contact_number')) {
            validate.showError('contact_number_error', 'Contact Number is required.');
            formError = true;
        } else if (!validateContactNumber(document.getElementById('contact_number').value)) {
            validate.showError('contact_number_error', 'Invalid Contact Number format.');
            formError = true;
        } else {
            validate.hideError('contact_number_error');
        }

        if (!validate.required('country')) {
            validate.showError('country_error', 'Select a country to continue.');
            formError = true;
        } else {
            validate.hideError('country_error');
        }

        // Validate that at least one checkbox is checked
        if (!validate.checked('channels', 1)) {
            validate.showError('channel_error', 'Select at least one channel to continue.');
            formError = true;
        } else {
            validate.hideError('channel_error');
        }

        // Validate that at least one country is selected
        if (!validate.checked('add_product', 1)) {
            validate.showError('product_error', 'Select at least one product to continue.');
            formError = true;
        } else {
            validate.hideError('product_error');
        }

        if (formError === true) {
            return;
        }

        // Function to validate contact number format
        function validateContactNumber(number) {
            const contactNumberRegex = /^\+?[\d\s\-()]{7,15}$/;
            return contactNumberRegex.test(number);
        }

        // Create new application with data provided
        let createAppForm = document.getElementsByClassName('app-form');
        let csrfToken = document.querySelector("input[name='_token']").value;
        let name = document.getElementById('name').value;
        let entity_name = document.getElementById('entity_name').value;
        let contact_number = document.getElementById('contact_number').value;
        let url = document.getElementById('url').value;
        let team = document.getElementById('team').value;
        let description = document.getElementById('description').value;
        let country = document.getElementById('country').value;

        let channels = document.querySelectorAll('input[name="channels[]"]:checked');
        let channels_array = [];
        channels.forEach((channel) => {
            channels_array.push(channel.value);
        });

        let products = document.querySelectorAll('input[name="add_product[]"]:checked');
        let products_array = [];
        products.forEach((product) => {
            products_array.push(product.value);
        });

        let loadingMessage = 'Saving New Application ...';
        let successMessage = 'Application created successfully';

        if(createAppForm.method==='PUT'){
            loadingMessage = 'Updating ...';
            successMessage = 'Application updated successfully';
        }

        addLoading(loadingMessage);

        let createFetch = fetch(createAppForm.action, {
            method: createAppForm.method,
            body: JSON.stringify({
                name: name,
                display_name: name,
                url: url,
                description: description,
                country: country,
                products: products_array,
                channels: channels_array,
                entity_name: entity_name,
                contact_number: contact_number,
                team: team,
            }),
            headers: {
                "Content-Type": "application/json; charset=utf-8",
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
            }
        });

        createFetch.then((response) => {
            if (response.status === 200) {
                addAlert('success', [successMessage, 'You will be redirected to your app page shortly.'], function () {
                    window.location.replace(createAppForm.dataset.redirect);
                });
            } else {
                addAlert('error', 'Something went wrong with creating a new application. If the error persists, contact' +
                    'the system administrator.')
            }
        });

        createFetch.finally(() => {
            removeLoading();
        });
    }
}());