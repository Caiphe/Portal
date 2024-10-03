document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let appAid = null;
    const regex = /^[a-zA-Z0-9_-]+$/; // Only allows alphanumeric characters, underscores, and dashes
    let tags = []; // For storing tags

    // Initialize event listeners
    function initializeEventListeners() {
        const container = document.querySelector('#table-data');
        container.addEventListener('click', function (event) {
            if (event.target.matches('.btn-show-edit-attribute-modal')) {
                appAid = event.target.getAttribute('data-edit-id');
                const attributeData = JSON.parse(event.target.getAttribute('data-attribute'));

                // Show the modal
                const editCustomAttribute = document.getElementById(`edit-custom-attributes-${appAid}`);
                if (editCustomAttribute) {
                    editCustomAttribute.classList.add('show');
                }

                setupModal(editCustomAttribute, attributeData); // Pass attributeData here
            }
        });
    }

    // Setup modal
    function setupModal(modal, attributeData) {
        const nameField = modal.querySelector('#name');
        const valueField = modal.querySelector('#value'); // Check this selector
        const numberField = modal.querySelector('#number-value');
        const booleanField = modal.querySelector('#boolean-value');
        const typeSelect = modal.querySelector('#type');
        const submitButton = modal.querySelector('.btn-confirm');
        const tagContainer = modal.querySelector('#tag-container');

        // Pre-fill the fields with data
        nameField.value = attributeData.name || ''; // Set name field
        if (valueField) {
            valueField.value = attributeData.value || ''; // Set value field if it exists
        } else {
            console.error('Value field not found in the modal.');
        }
        numberField.value = attributeData.numberValue || ''; // Set number field if applicable
        typeSelect.value = 'string'; // Default to 'string' if not present

        // Update tags if applicable
        if (typeof attributeData.value === 'string' && attributeData.value.includes(',')) {
            tags = attributeData.value.split(',').map(tag => tag.trim());
            handleAttributeTypeChange(modal);
            updateTagDisplay(modal, tags);
        }

        typeSelect.addEventListener('change', function () {
            handleAttributeTypeChange(modal);
            checkIfFormIsValid(modal, nameField, valueField, numberField, tags, submitButton);
        });

        numberField.addEventListener('keyup', function (event) {
            if (event.key === ' ' || event.key === ',') {
                const input = numberField.value.trim();
                if (input) {
                    const tagArray = input.split(/[, ]+/).filter(tag => tag !== '');
                    tags = tags.concat(tagArray);
                    numberField.value = '';
                    if (isTagDataValid(tags)) {
                        updateTagDisplay(modal, tags);
                        checkIfFormIsValid(modal, nameField, valueField, numberField, tags, submitButton);
                    } else {
                        tags = tags.slice(0, -tagArray.length);
                        addAlert('warning', 'The total size of tags exceeds 2KB.');
                    }
                }
            }
        });

        const form = modal.querySelector('#edit-custom-attribute-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            //submitForm(nameField, valueField, numberField, tags, modal);
            submitForm(nameField, valueField, numberField, booleanField, typeSelect, tags, modal);
        });

        handleAttributeTypeChange(modal); // Initial setup based on the default type
    }

    function handleAttributeTypeChange(modal) {
        const type = modal.querySelector('#type').value;
        const valueField = modal.querySelector('#value-field'); // Ensure the correct field
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

        checkIfFormIsValid(modal, modal.querySelector('#name'), modal.querySelector('#value'), modal.querySelector('#number-value'), tags, modal.querySelector('.btn-confirm'));
    }

    function updateTagDisplay(modal, tags) {
        const tagContainer = modal.querySelector('#tag-container');
        tagContainer.innerHTML = '';
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

        checkIfFormIsValid(modal, modal.querySelector('#name'), modal.querySelector('#value'), modal.querySelector('#number-value'), tags, modal.querySelector('.btn-confirm'));
    }

    function removeTag(index, modal, tags) {
        tags.splice(index, 1);
        updateTagDisplay(modal, tags);
    }

    function checkIfFormIsValid(modal, nameField, valueField, numberField, tags, submitButton) {
        const isNameValid = nameField.value.trim() !== '' && regex.test(nameField.value.trim()) && new Blob([nameField.value.trim()]).size <= 2048;
        let isValueValid = false;

        const attributeType = modal.querySelector('#type').value;

        // Check if boolean value exists in the modal
        const booleanValueField = modal.querySelector('#boolean-value');
        if (attributeType === 'string') {
            isValueValid = valueField.value.trim() !== '' && regex.test(valueField.value.trim()) && new Blob([valueField.value.trim()]).size <= 2048;
        } else if (attributeType === 'number') {
            isValueValid = isTagDataValid(tags);
        } else if (attributeType === 'boolean' && booleanValueField) {
            isValueValid = booleanValueField.value.trim() !== '';
        }

        // Validate form by enabling/disabling the submit button based on field validations
        if (isNameValid && (attributeType === 'boolean' ? isValueValid : (attributeType === 'number' ? isValueValid : true))) {
            submitButton.classList.remove('disabled');
            submitButton.disabled = false;
        } else {
            submitButton.classList.add('disabled');
            submitButton.disabled = true;
        }
    }
    // Helper functions
    function isTagDataValid(tags) {
        return tags.join(',').length <= 2048; // 2048 bytes limit for tags
    }

    function submitForm(nameField, valueField, numberField, booleanField, typeSelect, tags, modal) {
        const name = nameField.value.trim();
        let value = '';  // Value will change based on type
        const attributeType = typeSelect.value;

        // Handle the value based on attribute type
        if (attributeType === 'string') {
            value = valueField.value.trim();  // String type input
            if (!value) {  // Require a value for string type
                console.error('Value field must not be empty.');
                addAlert('error', 'Value field must not be empty.');
                return;
            }
        } else if (attributeType === 'number') {
            // Only accept a single number input, not tags
            value = tags.join(',');  // Collect tags entered in textarea
            if (!value) {  // Check if the value is a valid number
                console.error('Number field must contain a valid number.');
                addAlert('error', 'CSV String Array must contain comma separated characters.');
                return;
            }
        } else if (attributeType === 'boolean') {
            value = booleanField.value;  // True or False from select input
            if (value === '') {  // Require a value for boolean type
                console.error('Boolean field must not be empty.');
                addAlert('error', 'Boolean field must not be empty.');
                return;
            }
        }

        // Prepare the data in the simplified key-value format
        const attributeData = {
            [name]: value  // Just name: value
        };

        // Submit the form data using fetch (AJAX)
        fetch(`/admin/apps/${appAid}/custom-attributes/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Reuse the CSRF token declared earlier
            },
            body: JSON.stringify({
                attribute: attributeData  // Send data in simplified format
            })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // Parse the JSON response
            })
            .then(data => {
                if (data.success) {
                    console.log('Success:', data);
                    addAlert('success', 'Attribute saved');
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
                    console.error('Error:', data.message);
                    addAlert('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error); // Debugging: log any error
                addAlert('error', 'Failed to update custom attribute. Please try again.'); // Optionally handle error, e.g., show an error message
            });
    }
    // Initialize event listeners
    initializeEventListeners();
    function handleContentLoad() {
        initializeEventListeners(); // Reinitialize event listeners after new content is loaded
    }

    // Assuming pagination or AJAX content load triggers this function
    document.addEventListener('content-loaded', handleContentLoad); // Triggered when new content is loaded

});
