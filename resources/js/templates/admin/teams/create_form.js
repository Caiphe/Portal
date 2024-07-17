document.addEventListener('DOMContentLoaded', () => {
    const emailInput = document.getElementById('email-input');
    const emailContainer = document.getElementById('email-input-container');
    const tagsContainer = document.getElementById('tags-container');
    const inviteButton = document.getElementById('invite-button');
    const teamForm = document.getElementById('create-team');
    const emailListInput = document.getElementById('email-list');
    const teamOwnerSelect = document.getElementById('team-owner');
    const fileUploadInput = document.getElementById('file-upload');
    const imagePreview = document.getElementById('image-preview');
    let isFirstEmailSet = false;
    const filePreviews = document.getElementById('file-previews');

    fileUploadInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                filePreviews.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = '';
            filePreviews.style.display = 'none';
        }
    });

    emailInput.addEventListener('keyup', function(event) {
        if (event.key === 'Enter' || event.key === ',') {
            const emails = this.value.split(',');
            emails.forEach(email => {
                if (validateEmail(email.trim())) {
                    createEmailTag(email.trim());
                    updateTeamOwnerOptions();
                } else {
                    addAlert('warning', 'Email must be valid');
                }
            });
            this.value = '';
        }
    });

    emailContainer.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-tag')) {
            const tag = event.target.parentElement;
            removeEmailTag(tag);
            updateTeamOwnerOptions();
        }
    });

    tagsContainer.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-tag')) {
            const tag = event.target.parentElement;
            removeEmailTag(tag);
            updateTeamOwnerOptions();
        }
    });

    inviteButton.addEventListener('click', function() {
        const emails = emailInput.value.split(',');
        emails.forEach(email => {
            if (validateEmail(email.trim())) {
                createEmailTag(email.trim());
                updateTeamOwnerOptions();
            } else {
                addAlert('warning', 'Email must be valid');
            }
        });
        emailInput.value = '';
        updateHiddenInput();
    });

    teamForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const errors = validateForm(this);
        if (errors.length === 0) {
            updateHiddenInput();
            submitForm();
        } else {
            handleFormErrors(errors);
        }
    });

    /**
     * Validates a form and returns an array of errors if any.
     *
     * @param {HTMLFormElement} form - The form element to validate.
     * @return {string[]} An array of error messages, empty if no errors.
     */
    function validateForm(form) {
        const errors = [];
        const teamName = form['name'].value;
        const urlValue = form['url'].value;
        const contactNumber = form['contact'].value;
        const phoneRegex = /^\+|0(?:[0-9] ?){6,14}[0-9]$/;
        const teamOwner = form['team_owner'].value;

        if (teamName === '') errors.push("Team name required");
        if (urlValue === '') errors.push("Team URL required");
        if (urlValue !== '' && !/https?:\/\/.*\..*/.test(urlValue)) errors.push('Please add a valid team URL. Eg. https://url.com');
        if (contactNumber === '') errors.push("Contact number required");
        if (contactNumber !== '' && !phoneRegex.test(contactNumber)) errors.push("Valid phone number required");
        if (form['country'].value === '') errors.push("Country required");
        if (teamOwner === '') errors.push("Team owner is required");

        return errors;
    }

    /**
     * Creates an email tag element based on the provided email.
     *
     * @param {string} email - The email address to be displayed in the tag.
     */
    function createEmailTag(email) {
        if (!isEmailTagExists(email)) {
            const tag = document.createElement('div');
            tag.classList.add('email-tag');
            tag.innerHTML = `<span>${email}</span><span class="remove-tag">×</span>`;
            tagsContainer.appendChild(tag);
            updateHiddenInput();
        } else {
            addAlert('warning', `Email ${email} already exists.`);
        }
    }

    /**
     * Checks if an email tag exists in the DOM.
     *
     * @param {string} email - The email to check for.
     * @return {boolean} Returns true if the email tag exists, false otherwise.
     */
    function isEmailTagExists(email) {
        const tags = document.querySelectorAll('.email-tag span:first-child');
        return Array.from(tags).some(tag => tag.textContent === email);
    }

    /**
     * Removes an email tag from the DOM and updates hidden input and team owner options.
     *
     * @param {Element} tag - The tag element to be removed.
     */
    function removeEmailTag(tag) {
        const email = tag.querySelector('span:first-child').textContent;
        tag.remove();
        updateHiddenInput();
        updateTeamOwnerOptions();
    }

    /**
     * Updates the hidden input field with the email addresses from the email tags.
     *
     * @return {void} This function does not return anything.
     */
    function updateHiddenInput() {
        const tags = document.querySelectorAll('.email-tag span:first-child');
        const emails = Array.from(tags).map(tag => tag.textContent);
        emailListInput.value = emails.join(',');
    }

    /**
     * Updates the team owner options based on the email tags present in the DOM.
     *
     * @return {void} This function does not return anything.
     */
    function updateTeamOwnerOptions() {
        const tags = document.querySelectorAll('.email-tag span:first-child');
        const emails = Array.from(tags).map(tag => tag.textContent);

        // Clear existing options
        teamOwnerSelect.innerHTML = '';

        // Add placeholder option if no emails
        if (emails.length === 0) {
            const placeholderOption = document.createElement('option');
            placeholderOption.value = '';
            placeholderOption.disabled = true;
            placeholderOption.selected = true;
            placeholderOption.textContent = 'Please invite members to the team before selecting an owner';
            placeholderOption.style.color = '#808080'; // Gray color
            teamOwnerSelect.appendChild(placeholderOption);
        }

        // Add new options
        emails.forEach((email, index) => {
            const option = document.createElement('option');
            option.value = email;
            option.textContent = email;
            teamOwnerSelect.appendChild(option);
        });

        // Set the first email as the selected owner if not already set
        if (emails.length > 0) {
            teamOwnerSelect.value = emails[0];
        }

        // Reset the first email set flag if there are no emails
        if (emails.length === 0) {
            isFirstEmailSet = false;
        }
    }

    /**
     * Validates the format of an email.
     *
     * @param {string} email - The email address to be validated.
     * @return {boolean} Returns true if the email format is valid, false otherwise.
     */
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    /**
     * Submits the form data via XMLHttpRequest and handles the response accordingly.
     */
    function submitForm() {
        const formData = new FormData(teamForm);
        const xhr = new XMLHttpRequest();

        xhr.open('POST', teamForm.action, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('input[name="_token"]').value);

        xhr.onload = function() {
            removeLoading();

            if (xhr.status === 200) {
                addAlert('success', [`${formData.get('name')} has been successfully created. You will be redirected to your teams page shortly.`], function() {
                    window.location.href = "/admin/teams";
                });
            } else if (xhr.status === 429) {
                addAlert('warning', ["You are not allowed to create more than 2 teams per day."], function() {
                    window.location.href = "/admin/teams";
                });
            } else if (xhr.status === 413) {
                addAlert('warning', ["The logo dimensions are too large, please make sure the width and height are less than 2000."]);
            } else {
                const result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
                const messages = result && result.errors ? Object.values(result.errors).flat() : ['Sorry, there was a problem creating your team. Please try again.'];
                addAlert('error', messages);
            }
        };

        xhr.onerror = function() {
            removeLoading();
            addAlert('error', ['Sorry, there was a problem creating your team. Please try again.']);
        };

        addLoading('Creating a new team...');
        xhr.send(formData);
    }

    /**
     * Handles form errors by generating error messages from the input errors array and displaying an error alert.
     *
     * @param {Array} errors - An array of error messages.
     */
    function handleFormErrors(errors) {
        const errorMessages = errors.map(error => `<p>${error}</p>`).join('');
        addAlert('error', [errorMessages]);
    }

});
