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
        const valueField = modal.querySelector('#value');
        const numberField = modal.querySelector('#number-value');
        const typeSelect = modal.querySelector('#type');
        const submitButton = modal.querySelector('.btn-confirm');
        const tagContainer = modal.querySelector('#tag-container');

        // Pre-fill the fields with data
        nameField.value = attributeData.name || ''; // Set name field
        valueField.value = attributeData.value || ''; // Set value field (assuming your data has a value field)
        numberField.value = attributeData.numberValue || ''; // Set number field if applicable
        typeSelect.value =  'string'; // Set type (default to 'string' if not present)

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

        const form = modal.querySelector('#custom-attribute-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            submitForm(nameField, valueField, numberField, tags, modal);
        });

        handleAttributeTypeChange(modal); // Initial setup based on the default type
    }

    function handleAttributeTypeChange(modal) {
        const type = modal.querySelector('#type').value;
        const valueField = modal.querySelector('#value-field');
        const numberField = modal.querySelector('#number-field');
        const booleanField = modal.querySelector('#boolean-field');

        // Ensure fields are correctly accessed
        if (!valueField || !numberField || !booleanField) {
            console.error('One or more fields are not found in the modal.');
            return;
        }

        // Hide all fields initially
        valueField.style.display = 'none';
        numberField.style.display = 'none';
        booleanField.style.display = 'none';

        // Show the appropriate field based on the selected type
        if (type === 'string') {
            valueField.style.display = 'block';  // Show the regular value field
        } else if (type === 'number') {
            numberField.style.display = 'block';  // Show the number field
        } else if (type === 'boolean') {
            booleanField.style.display = 'block';  // Show the boolean field
            // Hide value and number fields explicitly when switching to boolean
            valueField.style.display = 'none';
            numberField.style.display = 'none';
        }

        // Call validation function
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

    function submitForm(nameField, valueField, numberField, tags, modal) {
        const attributeType = modal.querySelector('#type').value; // Get the selected type for the form submission
        const formData = {
            name: nameField.value,
            value: attributeType === 'boolean' ? modal.querySelector('#boolean-value').value : attributeType === 'number' ? tags : valueField.value,
        };

        // Make the API call or perform the submission logic here
        console.log('Submitting form with data:', formData);

        // Close the modal
        modal.classList.remove('show');
    }

    // Initialize event listeners
    initializeEventListeners();
});
