document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let appAid = null;
    const regex = /^[a-zA-Z0-9_-]+$/; // Only allows alphanumeric characters, underscores, and dashes (no spaces)
    let tags = []; // For storing tags from textarea

    // Function to initialize event listeners
    function initializeEventListeners() {
        const container = document.querySelector('#table-data'); // Use a common parent element

        container.addEventListener('click', function (event) {
            if (event.target.matches('.btn-show-attribute-modal')) {
                appAid = event.target.getAttribute('data-id');
                const addCustomAttributeModal = document.getElementById(`custom-attributes-${appAid}`);

                if (addCustomAttributeModal) {
                    addCustomAttributeModal.classList.add('show');
                    setupModal(addCustomAttributeModal); // Initialize modal fields and listeners
                } else {
                    console.error(`Modal with id custom-attributes-${appAid} not found`);
                }
            }

            if (event.target.matches('.btn-show-reserved-attribute-modal')) {
                const reservedAttributeId = event.target.getAttribute('reserved-data-id');
                const addReservedAttributeModal = document.getElementById(`reserved-attributes-${reservedAttributeId}`);
                if (addReservedAttributeModal) {
                    addReservedAttributeModal.classList.add('show');
                } else {
                    console.error(`Modal with id reserved-attributes-${reservedAttributeId} not found`);
                }
            }
        });
    }

    // Function to setup the modal
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
            console.error('One or more fields are not found in the modal.');
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
                        //alert('The total size of tags exceeds 2KB.');
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

        if (!regex.test(inputValue)) {
            errorField.textContent = errorMessage;
            errorField.style.display = 'block';
            submitButton.classList.add('disabled');
            submitButton.disabled = true;
        } else if (inputValue === '') {
            errorField.textContent = "This field is required.";
            errorField.style.display = 'block';
            submitButton.classList.add('disabled');
            submitButton.disabled = true;
        } else if (new Blob([inputValue]).size > 2048) { // Check size limit of 2KB
            errorField.textContent = "Input exceeds 2KB limit.";
            errorField.style.display = 'block';
            submitButton.classList.add('disabled');
            submitButton.disabled = true;
        } else {
            submitButton.classList.remove('disabled');
            errorField.style.display = 'none';
            checkIfFormIsValid(modal, modal.querySelector('#name'), modal.querySelector('#value'), modal.querySelector('#number-value'), modal.querySelector('#boolean-value'), tags, submitButton);
        }
    }

    function isTagDataValid(tags) {
        const tagString = tags.join(',');
        return new Blob([tagString]).size <= 2048 && tags.length <= 18;
    }

    function handleAttributeTypeChange(modal) {
        const type = modal.querySelector('#type').value;
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

        // Show appropriate fields based on type and set required attribute
        if (type === 'string') {
            valueField.style.display = 'block';
            valueInput.required = true;  // Only string field should be required
        } else if (type === 'number') {
            numberField.style.display = 'block';
            // Do not make textarea required since we are using tags validation
        } else if (type === 'boolean') {
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
        // Ensure fields are defined
        if (!nameField || !valueField || !numberField || !booleanField || !submitButton) {
            console.error('One or more fields are not found.');
            return;
        }

        const isNameValid = nameField.value.trim() !== '' && regex.test(nameField.value.trim()) && new Blob([nameField.value.trim()]).size <= 2048;
        let isValueValid = false;

        const attributeType = modal.querySelector('#type').value;
        if (attributeType === 'string') {
            isValueValid = valueField.value.trim() !== '' && regex.test(valueField.value.trim()) && new Blob([valueField.value.trim()]).size <= 2048;
        } else if (attributeType === 'number') {
            isValueValid = isTagDataValid(tags);
        } else if (attributeType === 'boolean') {
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

        // Handle the value based on attribute type
        if (attributeType === 'string') {
            value = valueField.value.trim();  // String type input
        } else if (attributeType === 'number') {
            value = tags.join(',');  // Collect tags entered in textarea
        } else if (attributeType === 'boolean') {
            value = booleanField.value;  // True or False from select input
        }

        if (!value && attributeType !== 'number') {  // Only require value if it's not a number type
            console.error('Value field must not be empty.');
            return;
        }

        // Prepare the data in the correct format
        const attributeData = {};
        attributeData[name] = value;  // Dynamically create key-value pair

        // Send the PUT request to the backend
        fetch(`/admin/apps/${appAid}/custom-attributes/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken  // Include CSRF token here
            },
            body: JSON.stringify({
                attribute: {
                    [attributeType]: [attributeData]  // Send data in the desired format
                }
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Success:', data);
                    // Optionally: Show success message or update UI
                    addAlert('success', 'Attribute saved');
                    // Close the modal after successful submission
                    modal.classList.remove('show');

                    // Optionally: Clear the form fields for future use
                    nameField.value = '';
                    valueField.value = '';
                    numberField.value = '';
                    booleanField.value = '';
                    tags = [];
                    updateTagDisplay(modal, tags); // Clear the tag display
                    checkIfFormIsValid(modal, nameField, valueField, numberField, booleanField, tags, submitButton); // Revalidate form
                } else {
                    console.error('Error:', data.message);
                    addAlert('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                addAlert('error', 'Failed to save attribute');
                // Optionally: Display error message to the user
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
