document.addEventListener('DOMContentLoaded', () => {
    const fileUploadInput = document.getElementById('file-upload');
    const imagePreview = document.getElementById('image-preview');
    const filePreviews = document.getElementById('file-previews');
    const teamForm = document.getElementById('create-team'); // Changed to update-team
    const teamNameInput = teamForm['name'];
    const contactNumberInput = teamForm['contact'];

    // Show the existing image preview if there is one
    if (imagePreview.src) {
        filePreviews.style.display = 'block';
    }

    // Team name validation
    teamNameInput.addEventListener('keyup', removeSpecialCharacters);
    function removeSpecialCharacters(){
        var specialChrs = /[`~!)@#$%(^&*|+=?;:±§'",.<>\{\}\[\]\\\/]/gi;
        this.value = this.value.replace(/  +/g, ' ');
    
        if(specialChrs.test(this.value)){
            this.value = this.value.replace(specialChrs, '');
            addAlert('warning', 'Team name cannot contain special characters.');
        }
    }

    // Contact number validation
    contactNumberInput.addEventListener('input', validatePhoneNumber);
    function validatePhoneNumber(){
        var specialChrs = /[a-z`~!)@#$%(^&*|=?;:±§'",.<>\{\}\[\]\\\/]/gi;
        var containsNonDigits = specialChrs.test(this.value);
    
        if(containsNonDigits){
            addAlert('warning', 'Character not allowed');
        }

        this.value = this.value.replace(specialChrs, "");
    }

    // Handle file upload preview
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

    // Handle form submission
    teamForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const errors = validateForm(this);
        if (errors.length === 0) {
            submitForm();
        } else {
            handleFormErrors(errors);
        }
    });

    /**
     * Validates the format of an email.
     *
     * @param {string} email - The email address to be validated.
     * @return {boolean} Returns true if the email format is valid, false otherwise.
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
                addAlert('success', [`${formData.get('name')} has been successfully updated.`], function() {
                    window.location.href = '/admin/teams';
                });
            } else if (xhr.status === 429) {
                addAlert('warning', ["You are not allowed to create more than 2 teams per day."], function() {
                    location.reload();
                });
            } else if (xhr.status === 413) {
                addAlert('warning', ["The logo dimensions are too large, please make sure the width and height are less than 2000."]);
            } else {
                const result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
                const messages = result && result.errors ? Object.values(result.errors).flat() : ['Sorry, there was a problem updating your team. Please try again.'];
                addAlert('error', messages);
            }
        };

        xhr.onerror = function() {
            removeLoading();
            addAlert('error', ['Sorry, there was a problem updating your team. Please try again.']);
        };

        addLoading('Updating your team...');
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
