document.addEventListener('DOMContentLoaded', function () {
    const showAttributeButtons = document.querySelectorAll('.btn-show-attribute-modal');
    const nameField = document.getElementById('name');
    const valueField = document.getElementById('value');
    const nameError = document.getElementById('name-error');
    const valueError = document.getElementById('value-error');
    const submitButton = document.querySelector('.btn-confirm');

    // Regex: Only allows alphanumeric characters, underscores, and dashes (no spaces)
    const regex = /^[a-zA-Z0-9_-]+$/;

    //Show Modal by ID
    showAttributeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const attributeId = this.getAttribute('data-id');
            const addCustomAttributeModal = document.getElementById(`custom-attributes-${attributeId}`);
            if (addCustomAttributeModal) {
                addCustomAttributeModal.classList.add('show');
            } else {
                console.error(`Modal with id custom-attributes-${attributeId} not found`);
            }
        });
    });

    function validateField(field, errorField, errorMessage) {
        field.addEventListener('input', function () {
            const inputValue = field.value.trim();
            if (!regex.test(inputValue)) {
                errorField.textContent = errorMessage;
                errorField.style.display = 'block';
                submitButton.disabled = true;
            } else if (inputValue === '') {
                errorField.textContent = "This field is required.";
                errorField.style.display = 'block';
                submitButton.disabled = true;
            } else {
                errorField.style.display = 'none';
                checkIfFormIsValid();
            }
        });
    }

    // Check if both fields are valid before enabling the submit button
    function checkIfFormIsValid() {
        const isNameValid = regex.test(nameField.value.trim()) && nameField.value.trim() !== '';
        const isValueValid = regex.test(valueField.value.trim());

        if (isNameValid && isValueValid) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }

    validateField(nameField, nameError, "Name can only contain letters, numbers, underscores, or dashes (no spaces allowed).");
    validateField(valueField, valueError, "Value can only contain letters, numbers, underscores, or dashes (no spaces allowed).");

    window.handleAttributeTypeChange = function () {
        const type = document.getElementById('type').value;
        const nameField = document.getElementById('name-field');
        const valueField = document.getElementById('value-field');
        const numberField = document.getElementById('number-field');
        const booleanField = document.getElementById('boolean-field');

        // Reset visibility
        nameField.style.display = 'block';
        valueField.style.display = 'none';
        numberField.style.display = 'none';
        booleanField.style.display = 'none';

        // Show appropriate fields based on type
        if (type === 'string') {
            valueField.style.display = 'block';
        } else if (type === 'number') {
            numberField.style.display = 'block';
        } else if (type === 'boolean') {
            booleanField.style.display = 'block';
        }
    };

    handleAttributeTypeChange();

    const numberTextarea = document.getElementById('number-value');
    const tagContainer = document.getElementById('tag-container');
    let tags = [];

    numberTextarea.addEventListener('keyup', function (event) {
        // Check if the user pressed space or comma
        if (event.key === ' ' || event.key === ',') {
            const input = numberTextarea.value.trim();
            if (input) {
                const tagArray = input.split(/[, ]+/).filter(tag => tag !== '');
                tags = tags.concat(tagArray);
                numberTextarea.value = '';  // Clear the textarea so we don't allow multiple tags on the same line tags
                updateTagDisplay();
            }
        }
    });

    function updateTagDisplay() {
        // Clear existing tags
        tagContainer.innerHTML = '';

        tags.forEach((tag, index) => {
            const tagElement = document.createElement('span');
            tagElement.classList.add('tag');
            tagElement.textContent = tag;


            const closeButton = document.createElement('span');
            closeButton.classList.add('close-button');
            closeButton.innerHTML = '&times;';
            closeButton.onclick = function () {
                removeTag(index);
            };

            tagElement.appendChild(closeButton);
            tagContainer.appendChild(tagElement);
        });
    }
    function removeTag(index) {
        tags.splice(index, 1);
        updateTagDisplay();
    }
});
