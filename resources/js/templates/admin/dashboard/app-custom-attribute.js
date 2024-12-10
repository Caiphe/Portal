document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let appAid = null;
    const regex = /^[a-zA-Z0-9_-]+$/; // Only allows alphanumeric characters, underscores, and dashes (no spaces)
    const forbiddenKeywords = ['displayName', 'sendermsisdn', 'originalChannelID', 'partnername', 'PermittedSenderIDs', 'PermittedPlanIDs', 'AutoRenewAllowed', 'country', 'teamname', 'location', 'description', 'Channels', 'EntityName', 'ContactNumber'];
    let tags = []; // For storing tags from textarea
    const restrictedKeywords = ['displayName', 'sendermsisdn', 'originalChannelID', 'partnername', 'PermittedSenderIDs', 'PermittedPlanIDs', 'AutoRenewAllowed', 'country', 'teamname', 'location', 'description', 'Channels', 'EntityName', 'ContactNumber'];

    function isRestricted(keyword) {
        return restrictedKeywords.some(restricted => restricted.toLowerCase() === keyword.toLowerCase());
    }

    function fetchAttributes(modal) {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const id = appAid;
        const url = `/admin/apps/${id}/custom-attributes/save`;

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
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    // Show the modal after fetching attributes
                    modal.classList.add('show');
                    setupModal(modal); // Initialize modal fields and listeners
                } else {
                    addAlert('error', 'Unexpected response format.');
                }
            })
            .catch(error => {
                removeLoading();
                addAlert('error', error.message || 'Sorry, there was a problem fetching your app attributes. Please try again.');
            });
    }

    // Function to initialize event listeners
    function initializeEventListeners() {
        const container = document.querySelector('#table-data');

        container.addEventListener('click', function (event) {
            if (event.target.matches('.btn-show-attribute-modal')) {
                appAid = event.target.getAttribute('data-id');
                const addCustomAttributeModal = document.getElementById(`custom-attributes-${appAid}`);

                if (addCustomAttributeModal) {
                    fetchAttributes(addCustomAttributeModal);
                }
            }
        });
    }


    // Function to set up the modal (unchanged)
    function setupModal(modal) {
        const nameField = modal.querySelector('#name');
        const valueField = modal.querySelector('#value');
        const numberField = modal.querySelector('#number-value');
        const booleanField = modal.querySelector('#boolean-value');
        const typeSelect = modal.querySelector('#type');
        const submitButton = modal.querySelector('.btn-confirm');
        const nameError = modal.querySelector('#name-error');
        const valueError = modal.querySelector('#value-error');

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
            validateField(nameField, nameError, "Name can only contain letters, numbers, underscores, or dashes (no spaces allowed).", modal, 'name');
        });

        // Validate Value Field
        valueField.addEventListener('input', function () {
            validateField(valueField, valueError, "Value can only contain letters, numbers, underscores, or dashes (no spaces allowed).", modal, 'value');
        });

        // Handle tags input for number values
        numberField.addEventListener('keyup', function (event) {
            if (event.key === 'Enter' || event.key === ',') {
                const input = numberField.value;

                if (input && !isEmptyOrWhitespace(input)) {

                    const tagArray = input.split(',').filter(tag => tag !== '');
                    // Add the valid tags to the tags array
                    tags = tags.concat(tagArray);
                    numberField.value = ''; // Clear the input field

                    // Validate tags and update the display
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
                    // If input is invalid, clear the input
                    numberField.value = '';
                }
            }
        });

        function isEmptyOrWhitespace(str) {
            return !str || str.trim().length === 0;
        }

        // Handle form submission
        const form = modal.querySelector('#custom-attribute-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();  // Prevent default form submission
            submitForm(nameField, valueField, numberField, booleanField, typeSelect, tags, modal);
        });

        handleAttributeTypeChange(modal);
    }

    // Add a keyword validation function
    function containsForbiddenKeyword(value) {
        const lowerValue = value.toLowerCase();
        return forbiddenKeywords.some(keyword => lowerValue.includes(keyword));
    }

    function validateField(field, errorField, errorMessage, modal, fieldType) {
        const inputValue = field.value.trim();
        const submitButton = modal.querySelector('.btn-confirm');

        // Name validation should not exceed 1KB (1024 bytes)
        const nameMaxSize = 1024;
        // Value validation should not exceed 2KB (2048 bytes)
        const valueMaxSize = 2048;

        let maxSize = fieldType === 'name' ? nameMaxSize : valueMaxSize;

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
        } else if (containsForbiddenKeyword(inputValue)) {
            errorField.textContent = `The ${fieldType} contains restricted keywords.`;
            errorField.style.display = 'block';
            submitButton.classList.add('disabled');
            submitButton.disabled = true;
        } else if (new Blob([inputValue]).size > maxSize) {
            errorField.textContent = `The ${fieldType} cannot exceeds ${fieldType === 'name' ? '1KB' : '2KB'} limit.`;
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
        const valueDescription = modal.querySelector('#value-description'); // Description element
        const typeDescription = modal.querySelector('#type-description'); // Description element

        // Reset visibility and required attributes
        valueField.style.display = 'none';
        numberField.style.display = 'none';
        booleanField.style.display = 'none';
        valueInput.required = false;
        booleanInput.required = false;  // Remove required from all inputs
        valueDescription.style.display = 'none';
        typeDescription.style.display = 'none';

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
            addAlert('error', 'One or more fields are not found in the modal.');
            return;
        }

        const isNameValid = nameField.value.trim() !== '' && regex.test(nameField.value.trim()) && new Blob([nameField.value.trim()]).size <= 2048 && !isRestricted(name);
        let isValueValid = false;
        const attributeType = modal.querySelector('#type').value;

        if (attributeType === 'string') {
            isValueValid = valueField.value.trim() !== '' && regex.test(valueField.value.trim()) && new Blob([valueField.value.trim()]).size <= 2048 && !isRestricted(name);
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
        const submitButton = modal.querySelector('.btn-confirm');

        const valueError = modal.querySelector('#tags-error');

        // Handle the value based on attribute type
        if (attributeType === 'string') {
            value = valueField.value.trim();  // String type input
        } else if (attributeType === 'number') {
            if (tags.length === 0) {
                validateField(valueField, valueError, "Tags field must not be empty. Type a comma or press Enter after the value to create CSV string array tags.", modal, 'value');
                valueError.style.display = 'block';
                //addAlert('error', 'Tags field must not be empty.');
                return;
            }
            value = tags.join(',');  // Collect tags entered in textarea
        } else if (attributeType === 'boolean') {
            value = booleanField.value;  // True or False from select input
        }

        if (!value && attributeType !== 'number') {  // Only require value if it's not a number type
            addAlert('error', 'Tags field value  must not be empty.');
            return;
        }

        if (isRestricted(name)) {
            addAlert('error', `You cannot use the name "${name}" of reserved or system attribute`);

            submitButton.classList.add('disabled');
            submitButton.disabled = true;
            return;
        }
        if (isRestricted(value)) {
            addAlert('error', `You cannot use the name "${value}" of a reserved or system attribute`);
            submitButton.classList.add('disabled');
            submitButton.disabled = true;
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
        addLoading('Adding Custom Attribute...');

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
                addAlert('error', error);
            })
            .finally(() => {
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

    // Handle content reinitialization after pagination
    function handleContentLoad() {
        initializeEventListeners(); // Reinitialize event listeners after new content is loaded
    }

    // Assuming pagination or AJAX content load triggers this function
    document.addEventListener('content-loaded', handleContentLoad); // Triggered when new content is loaded

});
