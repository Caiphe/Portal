document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let appAid = null;
    const regex = /^[a-zA-Z0-9_-]+$/; // Only allows alphanumeric characters, underscores, and dashes (no spaces)
    let tags = []; // For storing tags from textarea

    function fetchAttributes(modal) {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const id = appAid; // Use the global appAid variable
        const url = `/admin/apps/${id}/custom-attributes/save`; // Define your URL based on your routing

        const app = {
            _method: 'PUT',
            _token: token,
            aid: id
        };

        addLoading('Fetching custom attributes...');

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json; charset=utf-8',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(app)
        })
            .then(response => {
                removeLoading();
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(`Error: ${errorData.message}, Code: ${errorData.error_code}`);
                    });
                }
                return response.json(); // Parse the JSON response
            })
            .then(result => {
                // Check if the response indicates success
                if (result.success) {
                    // Handle successful response
                    modal.classList.add('show');
                    setupModal(modal); // Initialize modal fields and listeners
                } else {
                    // Handle unexpected response structure
                    addAlert('error', 'Unexpected response format.');
                }
            })
            .catch(error => {
                removeLoading(); // Ensure loading is removed even on error
                // Display specific error message
                addAlert('error', error.message || 'Sorry, there was a problem fetching your app attributes. Please try again.');
            });
    }

    // Function to initialize event listeners
    function initializeEventListeners() {
        const container = document.querySelector('#table-data'); // Use a common parent element

        container.addEventListener('click', function (event) {

            if (event.target.matches('.btn-show-reserved-attribute-modal')) {
                appAid = event.target.getAttribute('reserved-data-id');
                const addReservedAttributeModal = document.getElementById(`reserved-attributes-${appAid}`);

                if (addReservedAttributeModal) {
                    fetchAttributes(addReservedAttributeModal);
                } else {
                    addAlert('error', `Modal not found`);
                }
            }
        });
    }

    // Function to set up the modal
    function setupModal(modal) {
        const nameField = modal.querySelector('#name');
        const valueField = modal.querySelector('#value');
        const numberField = modal.querySelector('#number-value');
        const booleanField = modal.querySelector('#boolean-value');
        const typeSelect = modal.querySelector('#type');
        const submitButton = modal.querySelector('.btn-confirm');
        const nameError = modal.querySelector('#name-error');
        const valueError = modal.querySelector('#value-error');
        const tagContainer = modal.querySelector('#tag-container');

        // Ensure fields are defined
        if (!nameField || !valueField || !numberField || !booleanField || !typeSelect || !submitButton) {
            addAlert('error', 'One or more fields are not found in the modal.');
            return;
        }

        // Handle attribute type change
        typeSelect.addEventListener('change', function () {
            handleAttributeTypeChange(modal);
            checkIfFormIsValid(modal, nameField, valueField, numberField, booleanField, tags, submitButton);
        });

        // Validate Name Field
        nameField.addEventListener('input', function () {
            validateField(nameField, nameError, "Name can only contain letters, numbers, underscores, or dashes (no spaces allowed).", modal);
        });

        // Validate Value Field
        valueField.addEventListener('input', function () {
            validateField(valueField, valueError, "Value can only contain letters, numbers, underscores, or dashes (no spaces allowed).", modal);
        });

        // Handle tags input for number values
        numberField.addEventListener('keyup', function (event) {
            if (event.key === 'Enter' || event.key === ',') {
                const input = numberField.value;

                if (input && !isEmptyOrWhitespace(input)) {

                    const tagArray = input.split(',').filter(tag => tag !== '');
                    tags = tags.concat(tagArray);
                    numberField.value = '';

                    if (isTagDataValid(tags)) {
                        const tagError = modal.querySelector('#tags-error');
                        tagError.style.display = 'none';
                        updateTagDisplay(modal, tags);
                        checkIfFormIsValid(modal, nameField, valueField, numberField, booleanField, tags, submitButton);
                    } else {
                        addAlert('warning', 'The total size of tags exceeds 2KB.');
                        tags = tags.slice(0, -tagArray.length); // Remove the last added tags
                    }
                } else {
                    //addAlert('error', 'Tags cannot be empty or contain only spaces.');
                    numberField.value = ''; // Clear the input if it's invalid
                }
            }
        });

        // Function to check if a string is empty or whitespace
        function isEmptyOrWhitespace(str) {
            return !str || str.trim().length === 0;
        }

        // Handle form submission
        const form = modal.querySelector('#add-reserved-custom-attribute-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();  // Prevent default form submission
            submitForm(nameField, valueField, numberField, booleanField, typeSelect, tags, modal);
        });

        handleAttributeTypeChange(modal);
    }

    // Function to validate the field
    function validateField(field, errorField, errorMessage, modal) {
        const inputValue = field.value.trim();
        const submitButton = modal.querySelector('.btn-confirm');
        const noSpacesOrSpecialChars = /^[A-Za-z0-9]+$/; // For partnerName and originalChannelID
        const msisdnRegex = /^\+?[0-9]+$/; // Allows optional '+' and numeric values for senderMsisdn

        if (field.id === 'value' && modal.querySelector('#type').value === 'senderMsisdn') {
            // senderMsisdn-specific validation allowing the '+' sign
            if (!msisdnRegex.test(inputValue)) {
                errorField.textContent = "senderMsisdn should contain only numeric values or start with a + sign.";
                errorField.style.display = 'block';
                submitButton.classList.add('disabled');
                submitButton.disabled = true;
            } else if (inputValue === '') {
                errorField.textContent = "This field is required.";
                errorField.style.display = 'block';
                submitButton.classList.add('disabled');
                submitButton.disabled = true;
            } else if (new Blob([inputValue]).size > 2048) {
                errorField.textContent = "Input exceeds 2KB limit.";
                errorField.style.display = 'block';
                submitButton.classList.add('disabled');
                submitButton.disabled = true;
            } else {
                errorField.style.display = 'none';
            }
        } else if (field.id === 'value' && (modal.querySelector('#type').value === 'partnerName' || modal.querySelector('#type').value === 'originalChannelID')) {
            // partnerName and originalChannelID validation (no spaces or special characters)
            if (!noSpacesOrSpecialChars.test(inputValue)) {
                errorField.textContent = "This field cannot contain spaces or special characters.";
                errorField.style.display = 'block';
                submitButton.classList.add('disabled');
                submitButton.disabled = true;
            } else if (inputValue === '') {
                errorField.textContent = "This field is required.";
                errorField.style.display = 'block';
                submitButton.classList.add('disabled');
                submitButton.disabled = true;
            } else if (new Blob([inputValue]).size > 2048) {
                errorField.textContent = "Input exceeds 2KB limit.";
                errorField.style.display = 'block';
                submitButton.classList.add('disabled');
                submitButton.disabled = true;
            } else {
                errorField.style.display = 'none';
            }
        }

        // Trigger form validation after validating a field
        checkIfFormIsValid(modal, modal.querySelector('#name'), modal.querySelector('#value'), modal.querySelector('#number-value'), modal.querySelector('#boolean-value'), tags, submitButton);
    }

    function isTagDataValid(tags) {
        const tagString = tags.join(',');
        return new Blob([tagString]).size <= 2048 && tags.length <= 18;
    }

    // Function to handle attribute type changes and update name field
    function handleAttributeTypeChange(modal) {
        const typeSelect = modal.querySelector('#type');
        const nameField = modal.querySelector('#name');
        const valueField = modal.querySelector('#value-field');
        const numberField = modal.querySelector('#number-field');
        const booleanField = modal.querySelector('#boolean-field');
        const valueInput = modal.querySelector('#value');  // For string type
        const numberInput = modal.querySelector('#number-value');  // For number type
        const booleanInput = modal.querySelector('#boolean-value');  // For boolean type
        const valueDescription = modal.querySelector('#value-description');
        const typeDescription = modal.querySelector('#type-description');
        const valueError = modal.querySelector('#tags-error');
        // Reset visibility and required attributes
        valueField.style.display = 'none';
        numberField.style.display = 'none';
        booleanField.style.display = 'none';
        valueInput.required = false;
        booleanInput.required = false;  // Remove required from all inputs
        valueDescription.style.display = 'none';
        typeDescription.style.display = 'none';

        // Update name field based on selected type
        const selectedType = typeSelect.value;
        if (selectedType === 'senderMsisdn') {
            nameField.value = 'senderMsisdn';
            valueField.style.display = 'block';
            valueInput.required = true;

            typeDescription.textContent = "senderMsisdn should contain only numeric values.";
            typeDescription.style.display = 'block';
        } else if (selectedType === 'originalChannelID') {
            nameField.value = 'originalChannelID';
            valueField.style.display = 'block';
            valueInput.required = true;
        } else if (selectedType === 'partnerName') {
            nameField.value = 'partnerName';
            valueField.style.display = 'block';
            valueInput.required = true;
        } else if (selectedType === 'PermittedSenderIDs') {
            nameField.value = 'PermittedSenderIDs';
            numberField.style.display = 'block';
            valueDescription.style.display = 'block';
            valueDescription.textContent = "Create tags by typing a comma or pressing Enter after each value";
            valueError.style.display = 'none';
        } else if (selectedType === 'PermittedPlanIDs') {
            nameField.value = 'PermittedPlanIDs';
            numberField.style.display = 'block';
            valueDescription.style.display = 'block';
            valueDescription.textContent = "Create tags by typing a comma or pressing Enter after each value";
            valueError.style.display = 'none';
        } else if (selectedType === 'AutoRenewAllowed') {
            nameField.value = 'AutoRenewAllowed';
            booleanField.style.display = 'block';
            booleanInput.required = true;  // Boolean field should be required
        }

        // Ensure the form validation state is checked
        checkIfFormIsValid(modal, modal.querySelector('#name'), modal.querySelector('#value'), modal.querySelector('#number-value'), modal.querySelector('#boolean-value'), tags, modal.querySelector('.btn-confirm'));
    }

    function updateTagDisplay(modal, tags) {
        const tagContainer = modal.querySelector('#tag-container');
        tagContainer.innerHTML = '';  // Clear existing tags

        tags.forEach((tag, index) => {
            const tagElement = document.createElement('span');
            tagElement.classList.add('tag');
            tagElement.textContent = tag;

            const closeButton = document.createElement('span');
            closeButton.classList.add('close-button');
            closeButton.innerHTML = '&times;';
            closeButton.onclick = function () {
                removeTag(index, modal, tags);
            };

            tagElement.appendChild(closeButton);
            tagContainer.appendChild(tagElement);
        });

        checkIfFormIsValid(modal, modal.querySelector('#name'), modal.querySelector('#value'), modal.querySelector('#number-value'), modal.querySelector('#boolean-value'), tags, modal.querySelector('.btn-confirm'));
    }

    function removeTag(index, modal, tags) {
        tags.splice(index, 1);
        updateTagDisplay(modal, tags);
    }

    function checkIfFormIsValid(modal, nameField, valueField, numberField, booleanField, tags, submitButton) {
        const isNameValid = nameField.value.trim() !== '' && regex.test(nameField.value.trim()) && new Blob([nameField.value.trim()]).size <= 2048;
        let isValueValid = false;

        if (!nameField || !valueField || !numberField || !booleanField || !submitButton) {
            addAlert('error', 'One or more fields are not found.');
            return;
        }

        const attributeType = modal.querySelector('#type').value;

        // Updated regex for senderMsisdn: allows numeric values and the "+" sign
        const msisdnRegex = /^[0-9+]+$/;
        const noSpacesOrSpecialChars = /^[A-Za-z0-9]+$/;

        // Validate Value Field based on the selected attribute type
        if (attributeType === 'senderMsisdn') {
            // senderMsisdn validation: allows numeric values and the "+" sign
            isValueValid = valueField.value.trim() !== '' && msisdnRegex.test(valueField.value.trim());
        } else if (attributeType === 'partnerName' || attributeType === 'originalChannelID') {
            // partnerName and originalChannelID: no spaces or special characters
            isValueValid = valueField.value.trim() !== '' && noSpacesOrSpecialChars.test(valueField.value.trim()) && new Blob([valueField.value.trim()]).size <= 2048;
        } else if (attributeType === 'PermittedSenderIDs' || attributeType === 'PermittedPlanIDs') {
            // Validate tag data for PermittedSenderIDs and PermittedPlanIDs
            isValueValid = isTagDataValid(tags);
        } else if (attributeType === 'AutoRenewAllowed') {
            // Boolean validation for AutoRenewAllowed
            isValueValid = booleanField.value !== '';
        }

        // Enable or disable the submit button based on validation status
        if (isNameValid && isValueValid) {
            submitButton.classList.remove('disabled');
            submitButton.disabled = false;
        } else {
            submitButton.classList.add('disabled');
            submitButton.disabled = true;
        }
    }

    function submitForm(nameField, valueField, numberField, booleanField, typeSelect, tags, modal) {
        const name = nameField.value.trim();
        let value = '';  // Value will change based on type
        const attributeType = typeSelect.value;
        const submitButton = modal.querySelector('.btn-confirm');

        const valueError = modal.querySelector('#tags-error');

        // Handle the value based on attribute type
        if (attributeType === 'senderMsisdn') {
            value = valueField.value.trim();
        } else if (attributeType === 'partnerName') {
            value = valueField.value.trim();
        } else if (attributeType === 'originalChannelID') {
            value = valueField.value.trim();
        } else if (attributeType === 'PermittedSenderIDs') {
            if (tags.length === 0) {
                console.log("i am here")
                valueError.textContent = "Tags field must not be empty. Type a comma or press Enter after the value to create CSV string array tags.";
                valueError.style.display = 'block';
                return;
            }
            value = tags.join(',');
        } else if (attributeType === 'PermittedPlanIDs') {
            if (tags.length === 0) {
                valueError.textContent = "Tags field must not be empty. Type a comma or press Enter after the value to create CSV string array tags.";
                valueError.style.display = 'block';
                //addAlert('error', 'Tags field must not be empty.');
                return;
            }
            value = tags.join(',');
        } else if (attributeType === 'AutoRenewAllowed') {
            value = booleanField.value;  // True or False from select input
        }

        if (!value && attributeType !== 'AutoRenewAllowed') {  // Only require value if it's not a number type
            addAlert('error', 'Value field must not be empty. You need to add tags.');
            return;
        }

        // Disable the submit button to prevent multiple submissions
        submitButton.classList.add('disabled');
        submitButton.disabled = true;

        // Prepare the data in the simplified key-value format
        const attributeData = {
            [name]: value  // Just name: value
        };

        // Display loading message
        addLoading('Adding Reserved Attribute...');

        // Send the PUT request to the backend
        fetch(`/admin/apps/${appAid}/save-custom-attributes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken  // Include CSRF token here
            },
            body: JSON.stringify({
                attribute: attributeData  // Send data in simplified format
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addAlert('success', data.message);
                    modal.classList.remove('show');

                    // Clear the form fields for future use
                    nameField.value = '';
                    valueField.value = '';
                    numberField.value = '';
                    booleanField.value = '';
                    tags = [];
                    updateTagDisplay(modal, tags); // Clear the tag display
                    checkIfFormIsValid(modal, nameField, valueField, numberField, booleanField, tags, submitButton); // Revalidate form
                } else {
                    addAlert('error', data.message);
                }
            })
            .catch(error => {
                addAlert('error', 'Failed to save attribute');
            }).finally(() => {
            removeLoading();
            setTimeout(function () {
                // Re-enable the submit button after form submission is complete
                submitButton.classList.remove('disabled');
                submitButton.disabled = false;

                window.location.reload();
            }, 3000); // 3000 milliseconds = 3 seconds
        });
    }

    // Initialize event listeners
    initializeEventListeners();

    // Handle content reinitialization after pagination
    function handleContentLoad() {
        initializeEventListeners(); // Reinitialize event listeners after new content is loaded
    }

    // Assuming pagination or AJAX content load triggers this function
    document.addEventListener('content-loaded', handleContentLoad); // Triggered when new content is loaded

});
