document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let appAid = null;
    const regex = /^[a-zA-Z0-9_-]+$/; // Only allows alphanumeric characters, underscores, and dashes
    let tags = []; // For storing tags
    // Restricted keywords (case-insensitive)
    const restrictedKeywords = ['sendermsisdn', 'originalchannelids', 'partnername', 'permittedsenderids', 'permittedplanids', 'autorenewallowed', 'country', 'teamname', 'location', 'description', 'displayname'];

    // Helper function to check if the name or value is restricted
    function isRestricted(keyword) {
        return restrictedKeywords.find(restricted => restricted.toLowerCase() === keyword.toLowerCase());
    }

    // Add live validation on keyup event for the name and value fields
    function addLiveValidation(nameField, valueField, submitButton) {
        nameField.addEventListener('keyup', function () {
            validateFields(nameField, valueField, submitButton);
        });
        valueField.addEventListener('keyup', function () {
            validateFields(nameField, valueField, submitButton);
        });
    }

    function validateFields(nameField, valueField, submitButton) {
        const name = nameField.value.trim();
        const value = valueField.value.trim();

        const restrictedName = isRestricted(name);
        const restrictedValue = isRestricted(value);

        const nameSize = new Blob([name]).size;
        const valueSize = new Blob([value]).size;

        const isNameValid = regex.test(name) && !restrictedName && nameSize <= 1024; // 1KB limit
        const isValueValid = regex.test(value) && !restrictedValue && valueSize <= 2048; // 2KB limit

        if (isNameValid && isValueValid) {
            submitButton.classList.remove('disabled');
            submitButton.disabled = false;
        } else {
            submitButton.classList.add('disabled');
            submitButton.disabled = true;
        }

        // Dynamically handle error messages
        toggleError(nameField, isNameValid, restrictedName ? `The name "${name}" contains restricted keywords.` : nameSize > 1024 ? 'Name cannot exceeds 1KB limit.' : 'Invalid name. Only letters, numbers, underscores, and dashes are allowed.');
        toggleError(valueField, isValueValid, restrictedValue ? `The value "${value}" contains restricted keywords.` : valueSize > 2048 ? 'Value cannot exceeds 2KB limit.' : 'Invalid value. Only letters, numbers, underscores, and dashes are allowed.');
    }


    function toggleError(field, isValid, errorMessage) {
        const errorContainer = field.nextElementSibling; // Assume the error message is in a sibling element
        if (!isValid) {
            errorContainer.textContent = errorMessage;
            errorContainer.style.display = 'block';
        } else {
            errorContainer.style.display = 'none';
        }
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

        return fetch(url, {  // Return the fetch promise
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
                if (result.success) {
                    return result; // Return the result for chaining
                } else {
                    addAlert('error', 'Unexpected response format.');
                    throw new Error('Unexpected response format.');
                }
            })
            .catch(error => {
                removeLoading();
                addAlert('error', error.message || 'Sorry, there was a problem fetching your app attributes. Please try again.');
                throw error; // Propagate error for handling
            });
    }

    // Initialize event listeners
    function initializeEventListeners() {
        const container = document.querySelector('#table-data');
        container.addEventListener('click', function (event) {
            if (event.target.matches('.btn-show-edit-attribute-modal')) {
                appAid = event.target.getAttribute('data-edit-id');
                const attributeData = JSON.parse(event.target.getAttribute('data-attribute'));

                fetchAttributes() // Fetch attributes first
                    .then(result => {
                        // Show the modal and setup after fetch success
                        const editCustomAttribute = document.getElementById(`edit-custom-attributes-${appAid}`);
                        if (editCustomAttribute) {
                            editCustomAttribute.classList.add('show');
                        }
                        setupModal(editCustomAttribute, attributeData); // Set up modal with fetched data
                    })
                    .catch(error => {
                        addAlert('error', error.message || 'Sorry, there was a problem fetching your app attributes. Please try again.');
                    });
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

        // Pre-fill the fields with data
        nameField.value = attributeData.name || ''; // Set name field

        if (valueField) {
            valueField.value = attributeData.value || ''; // Set value field if it exists
        } else {
            addAlert('error', 'Value field not found in the modal.');
        }
        numberField.value = attributeData.numberValue || '';

        addLiveValidation(nameField, valueField, submitButton); // Add live validation

        // Initial validation check
        validateFields(nameField, valueField, submitButton);

        // Determine the type based on the valueField
        const value = valueField.value.trim();
        if (value.includes(',')) {
            typeSelect.value = 'number'; // Set type to number if valueField contains a comma
        } else if (value === 'true' || value === 'false') {
            typeSelect.value = 'boolean'; // Set type to boolean if valueField is true or false
        } else {
            typeSelect.value = 'string'; // Default to string
        }

        numberField.value = attributeData.numberValue || ''; // Set number field if applicable

        // Pre-fill textarea and tags for Number
        if (typeSelect.value === 'number') {
            // Check if value is an existing tag list
            numberField.value = ''; // Pre-fill with existing value
            if (attributeData.value) {
                tags = attributeData.value.split(',').map(tag => tag.trim());
                //numberField.value = tags.join(', '); // Pre-fill the textarea with existing tags
            }
            handleAttributeTypeChange(modal); // Ensure correct display of fields
            updateTagDisplay(modal, tags); // Update tag display
        }

        // Update tags if applicable

        if (typeof attributeData.value === nameField.value && attributeData.value.includes(',')) {
            tags = attributeData.value.split(',').map(tag => tag.trim());
            handleAttributeTypeChange(modal);
            updateTagDisplay(modal, tags);
        }

        typeSelect.addEventListener('change', function () {
            handleAttributeTypeChange(modal);
            checkIfFormIsValid(modal, nameField, valueField, numberField, tags, submitButton);
        });

        numberField.addEventListener('keyup', function (event) {
            if (event.key === 'Enter' || event.key === ',') {
                const input = numberField.value;
                if (input && !isEmptyOrWhitespace(input)) {

                    const tagArray = input.split(',').filter(tag => tag !== '');
                    tags = tags.concat(tagArray);
                    numberField.value = '';

                    // Check if tag data is valid
                    if (isTagDataValid(tags)) {
                        const tagError = modal.querySelector('#tags-error');
                        tagError.style.display = 'none';
                        updateTagDisplay(modal, tags); // Update tag display in UI
                        checkIfFormIsValid(modal, nameField, valueField, numberField, tags, submitButton); // Check form validity
                    } else {
                        tags = tags.slice(0, -tagArray.length); // Revert if tags exceed limit
                        addAlert('warning', 'The total size of tags exceeds 2KB.');
                    }
                } else {
                    numberField.value = ''; // Clear invalid input
                }
            }
        });

        const form = modal.querySelector('#edit-custom-attribute-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            submitForm(nameField, valueField, numberField, booleanField, typeSelect, tags, modal);
        });

        handleAttributeTypeChange(modal); // Initial setup based on the default type
    }

    function isEmptyOrWhitespace(str) {
        return !str || str.trim().length === 0;
    }

    function handleAttributeTypeChange(modal) {
        const type = modal.querySelector('#type').value;
        const valueField = modal.querySelector('#value-field'); // Ensure the correct field
        const numberField = modal.querySelector('#number-field');
        const booleanField = modal.querySelector('#boolean-field');
        const valueInput = modal.querySelector('#value');  // For string type
        const numberInput = modal.querySelector('#number-value');  // For number type
        const booleanInput = modal.querySelector('#boolean-value');  // For boolean type
        const typeDescription = modal.querySelector('#type-description');
        const valueDescription = modal.querySelector('#value-description');

        // Reset visibility and required attributes
        valueField.style.display = 'none';
        numberField.style.display = 'none';
        booleanField.style.display = 'none';
        valueDescription.style.display = 'none';
        typeDescription.style.display = 'none';
        valueInput.required = false;
        booleanInput.required = false;  // Remove required from all inputs

        // Show appropriate fields based on type and set required attribute
        if (type === 'string') {
            valueField.style.display = 'block';
            valueInput.required = true;
            typeDescription.textContent = "A string attribute is the default type of attribute and only accepts a text value without special characters or spaces.";
            typeDescription.style.display = 'block';
        } else if (type === 'number') {
            numberField.style.display = 'block';
            valueDescription.textContent = "Create tags by typing a comma or pressing Enter after each value";
            valueDescription.style.display = 'block';
            typeDescription.textContent = "A CSV String attribute contains text values seperated by \",\". Special characters and spaces are not allowed.";
            typeDescription.style.display = 'block';
        } else if (type === 'boolean') {
            booleanField.style.display = 'block';
            booleanInput.required = true;
            typeDescription.textContent = "A boolean has a true or false value only.";
            typeDescription.style.display = 'block';
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
        const name = nameField.value.trim();
        const isNameValid = name !== '' && regex.test(name) && new Blob([name]).size <= 2048 && !isRestricted(name); // Check if name is restricted

        let isValueValid = false;

        const attributeType = modal.querySelector('#type').value;

        // Check if boolean value exists in the modal
        const booleanValueField = modal.querySelector('#boolean-value');
        if (attributeType === 'string') {
            const value = valueField.value.trim();
            isValueValid = value !== '' && regex.test(value) && new Blob([value]).size <= 2048 && !isRestricted(value); // Check if value is restricted
        } else if (attributeType === 'number') {
            isValueValid = isTagDataValid(tags);
        } else if (attributeType === 'boolean' && booleanValueField) {
            isValueValid = booleanValueField.value.trim() !== '';
        }

        // Enable or disable the submit button based on form validity
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
        const submitButton = modal.querySelector('.btn-confirm');

        const valueError = modal.querySelector('#tags-error');

        // Disable the submit button to prevent multiple submissions
        submitButton.classList.add('disabled');
        submitButton.disabled = true;

        // Handle the value based on attribute type
        if (attributeType === 'string') {
            value = valueField.value.trim();  // String type input
        } else if (attributeType === 'number') {
            if (tags.length === 0) {
                valueError.textContent = "Tags field must not be empty. Type a comma or press Enter after the value to create CSV string array tags.";
                valueError.style.display = 'block';
                //addAlert('error', 'Tags field must not be empty.');
                return;
            }
            value = tags.join(',');  // Collect tags entered in textarea
        } else if (attributeType === 'boolean') {
            value = booleanField.value;  // True or False from select input
        }

        // Perform an additional check for restricted names or values
        if (isRestricted(name)) {
            addAlert('error', `The attribute name "${name}" is not allowed.`);
            return;
        }
        if (isRestricted(value)) {
            addAlert('error', `The attribute value "${value}" is not allowed.`);
            return;
        }


        // Prepare the data in the simplified key-value format
        const attributeData = {
            [name]: value  // Just name: value
        };

        // Display loading message
        addLoading('Uppdating Custom Attribute...');

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
                addAlert('error', 'Failed to update custom attribute. Please try again.'); // Optionally handle error, e.g., show an error message
            }).finally(() => {
            // Re-enable the submit button after form submission is complete
            submitButton.classList.remove('disabled');
            submitButton.disabled = false;

            removeLoading();
            setTimeout(function () {
                window.location.reload();
            }, 3000); // 3000 milliseconds = 3 seconds
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
