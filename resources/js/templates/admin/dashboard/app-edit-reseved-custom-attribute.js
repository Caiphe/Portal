document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let appAid = null;
    const regex = /^[a-zA-Z0-9_-]+$/; // Only allows alphanumeric characters, underscores, and dashes (no spaces)
    let tags = []; // For storing tags from textarea

    // Function to initialize event listeners
    function initializeEventListeners() {
        const container = document.querySelector('#table-data'); // Use a common parent element

        container.addEventListener('click', function (event) {

            if (event.target.matches('.btn-show-edit-reserved-attribute-modal')) {
                appAid = event.target.getAttribute('data-edit-id');
                const addReservedAttributeModal = document.getElementById(`edit-reserved-attribute-${appAid}`);
                const attributeData = JSON.parse(event.target.getAttribute('data-attribute'));

                if (addReservedAttributeModal) {
                    fetchAttributes().then(() => {
                        addReservedAttributeModal.classList.add('show'); // Show the modal after attributes are fetched
                        setupModal(addReservedAttributeModal, attributeData);
                    }).catch(error => {
                        addAlert('error', error.message || 'There was a problem fetching the app attributes.');
                    });
                }
            }
        });
    }

    function fetchAttributes() {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const id = appAid; // Use the global appAid variable
        const url = `/admin/apps/${id}/custom-attributes/save`; // Define your URL based on your routing

        const app = {
            _method: 'PUT',
            _token: token,
            aid: id
        };

        addLoading('Fetching custom attributes...');

        return fetch(url, {
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

                } else {
                    // Handle unexpected response structure
                    addAlert('error', 'Unexpected response format from the Apigee.');
                }
            })
            .catch(error => {
                removeLoading(); // Ensure loading is removed even on error
                throw error; // Rethrow error to handle in .catch of event listener
            });
    }

    // Function to set up the modal
    function setupModal(modal, attributeData) {
        const nameField = modal.querySelector('#name');
        const valueField = modal.querySelector('#value');
        const numberField = modal.querySelector('#number-value');
        const booleanField = modal.querySelector('#boolean-value');
        const typeSelect = modal.querySelector('#type');
        const submitButton = modal.querySelector('.btn-confirm');
        const nameError = modal.querySelector('#name-error');
        const valueError = modal.querySelector('#value-error');

        // Pre-fill the fields with data
        nameField.value = attributeData.name || ''; // Set name field

        if (valueField) {
            valueField.value = attributeData.value || ''; // Set value field if it exists
        } else {
            addAlert('error', 'Value field not found in the modal.');
        }
        numberField.value = attributeData.numberValue || ''; // Set number field if applicable

        // Update the typeSelect to match the attribute type
        typeSelect.value = attributeData.name; // Default to the attribute name

        // Update tags if applicable
        if (typeSelect.value === 'permittedSenderIDs') {
            // Pre-fill textarea and tags for permittedSenderIDs
            valueField.value = attributeData.value || ''; // Pre-fill with existing value
            if (attributeData.value && attributeData.value.includes(',')) {
                tags = attributeData.value.split(',').map(tag => tag.trim());
            }
            handleAttributeTypeChange(modal); // Ensure correct display of fields
            updateTagDisplay(modal, tags); // Update tag display
        }

        if (typeSelect.value === 'permittedPlanIDs' || typeSelect.value === 'permittedPlanIDs') {
            // Pre-fill textarea and tags for permittedSenderIDs
            valueField.value = attributeData.value || ''; // Pre-fill with existing value
            if (attributeData.value && attributeData.value.includes(',')) {
                tags = attributeData.value.split(',').map(tag => tag.trim());
            }
            handleAttributeTypeChange(modal); // Ensure correct display of fields
            updateTagDisplay(modal, tags); // Update tag display
        }

        // Ensure fields are defined
        if (!nameField || !valueField || !numberField || !booleanField || !typeSelect || !submitButton) {
            addAlert('error', 'One or more fields are not found in the modal to add a custom attribute.');
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
            if (event.key === ' ' || event.key === ',') {
                const input = numberField.value.trim();
                if (input) {
                    const tagArray = input.split(/[, ]+/).filter(tag => tag !== '');
                    tags = tags.concat(tagArray);
                    numberField.value = '';  // Clear the textarea
                    // Check total size of tags
                    if (isTagDataValid(tags)) {
                        updateTagDisplay(modal, tags);
                        checkIfFormIsValid(modal, nameField, valueField, numberField, booleanField, tags, submitButton);
                    } else {
                        addAlert('warning', 'The total size of tags exceeds 2KB.');
                        tags = tags.slice(0, -tagArray.length); // Remove the last added tags
                    }
                }
            }
        });

        // Handle form submission
        const form = modal.querySelector('#custom-attribute-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();  // Prevent default form submission
            submitForm(nameField, valueField, numberField, booleanField, typeSelect, tags, modal);
        });

        handleAttributeTypeChange(modal);
    }

    function validateField(field, errorField, errorMessage, modal) {
        const inputValue = field.value.trim();
        const submitButton = modal.querySelector('.btn-confirm');
        const numericRegex = /^[0-9]+$/; // Numeric validation

        if (field.id === 'value' && modal.querySelector('#type').value === 'senderMsisdn') {
            // SenderMsisdn-specific validation
            if (!numericRegex.test(inputValue)) {
                errorField.textContent = "senderMsisdn should contain only numeric values.";
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

    function handleAttributeTypeChange(modal) {
        const typeSelect = modal.querySelector('#type');
        const nameField = modal.querySelector('#name');
        const valueField = modal.querySelector('#value-field');
        const numberField = modal.querySelector('#number-field');
        const booleanField = modal.querySelector('#boolean-field');

        const valueInput = modal.querySelector('#value');  // For string type
        const numberInput = modal.querySelector('#number-value');  // For number type
        const booleanInput = modal.querySelector('#boolean-value');  // For boolean type

        // Reset visibility and required attributes
        valueField.style.display = 'none';
        numberField.style.display = 'none';
        booleanField.style.display = 'none';
        valueInput.required = false;
        booleanInput.required = false;  // Remove required from all inputs

        // Update name field based on selected type
        const selectedType = typeSelect.value;
        if (selectedType === 'senderMsisdn') {
            nameField.value = 'senderMsisdn';
            valueField.style.display = 'block';
            valueInput.required = true;
        } else if (selectedType === 'originalChannelIDs') {
            nameField.value = 'originalChannelIDs';
            valueField.style.display = 'block';
            valueInput.required = true;
        }else if(selectedType === 'partnerName'){
            nameField.value = 'partnerName';
            valueField.style.display = 'block';
            valueInput.required = true;
        } else if (selectedType === 'permittedSenderIDs') {
            nameField.value = 'permittedSenderIDs';
            numberField.style.display = 'block';
            // Do not make textarea required since we are using tags validation
        } else if (selectedType === 'permittedPlanIDs') {
            nameField.value = 'permittedPlanIDs';
            numberField.style.display = 'block';
            // Do not make textarea required since we are using tags validation
        } else if (selectedType === 'autoRenewAllowed') {
            nameField.value = 'autoRenewAllowed';
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
            addAlert('error', 'One or more fields are not found to validate.');
            return;
        }

        const attributeType = modal.querySelector('#type').value;
        // Validate Value Field (specific for senderMsisdn)
        if (attributeType === 'senderMsisdn') {
            const numericRegex = /^[0-9]+$/; // Allows only numeric values
            isValueValid = valueField.value.trim() !== '' && numericRegex.test(valueField.value.trim());
        } else if (attributeType === 'partnerName') {
            isValueValid = valueField.value.trim() !== '' && regex.test(valueField.value.trim()) && new Blob([valueField.value.trim()]).size <= 2048;
        } else if (attributeType === 'originalChannelIDs') {
            isValueValid = valueField.value.trim() !== '' && regex.test(valueField.value.trim()) && new Blob([valueField.value.trim()]).size <= 2048;
        } else if (attributeType === 'permittedSenderIDs') {
            isValueValid = isTagDataValid(tags);
        } else if (attributeType === 'permittedPlanIDs') {
            isValueValid = isTagDataValid(tags);
        } else if (attributeType === 'autoRenewAllowed') {
            isValueValid = booleanField.value.trim() !== '';
        }

        // Validate form by enabling/disabling the submit button based on field validations
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

        // Disable the submit button to prevent multiple submissions
        submitButton.classList.add('disabled');
        submitButton.disabled = true;

        // Handle the value based on attribute type
        if (attributeType === 'senderMsisdn') {
            value = valueField.value.trim();
        }else if (attributeType === 'partnerName') {
            value = valueField.value.trim();
        } else if (attributeType === 'originalChannelIDs') {
            value = valueField.value.trim();
        } else if (attributeType === 'permittedSenderIDs') {
            value = tags.join(',');
        } else if (attributeType === 'permittedPlanIDs') {
            value = tags.join(',');
        } else if (attributeType === 'autoRenewAllowed') {
            value = booleanField.value;  // True or False from select input
        }

        if (!value && attributeType !== 'permittedSenderIDs' || !value && attributeType !== 'permittedPlanIDs') {
           // Only require value if it's not a number type
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

        // Show the loading message before starting the request
        addLoading('Updating Reserved Attribute...');

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
            // Re-enable the submit button after form submission is complete
            submitButton.classList.remove('disabled');
            submitButton.disabled = false;

            setTimeout(function() {
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

