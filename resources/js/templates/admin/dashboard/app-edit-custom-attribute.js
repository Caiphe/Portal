document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let appAid = null;

    // Function to initialize event listeners
    function initializeEventListeners() {
        const container = document.querySelector('#table-data');

        container.addEventListener('click', function (event) {
            if (event.target.matches('.btn-show-edit-attribute-modal')) {
                appAid = event.target.getAttribute('data-edit-id');
                const attributeData = JSON.parse(event.target.getAttribute('data-attribute'));

                openEditAttributeDialog(attributeData);  // Open modal and pass attribute data
            }
        });
    }

    // Function to open the modal and populate it with attribute fields
    function openEditAttributeDialog(attributes) {
        // Clear the existing form fields
        const attributeContainer = document.getElementById('attribute-fields-container');
        attributeContainer.innerHTML = '';

        // Loop through the attributes object and create fields
        for (const [key, value] of Object.entries(attributes)) {
            // Create a form group for each key-value pair
            const formGroup = document.createElement('div');
            formGroup.classList.add('form-group');

            // Create a label
            const label = document.createElement('label');
            label.textContent = key;
            formGroup.appendChild(label);

            // Create the input field based on the value's type
            let input;
            if (typeof value === 'boolean') {
                input = document.createElement('input');
                input.type = 'checkbox';
                input.checked = value; // Set the checkbox value
            } else {
                input = document.createElement('input');
                input.type = 'text';
                input.value = value; // Set the text field value
            }

            // Set the name attribute to match the key
            input.classList.add('form-control');
            input.name = key;
            formGroup.appendChild(input);


            attributeContainer.appendChild(formGroup);
        }

        // Show the modal
        const dialog = document.getElementById(`edit-custom-attributes-${appAid}`);
        if (dialog) {
            dialog.classList.add('show');
        }
    }

    // Update attributes based on identifier
    function updateAttributes(appAid) {
        const attributeFieldsContainer = document.querySelector('#attribute-fields-container');
        const inputFields = attributeFieldsContainer.querySelectorAll('input');
        const payload = { attributes: {} };

        // Loop through input fields and build the payload
        inputFields.forEach(input => {
            const key = input.name;
            let value;

            if (input.type === 'checkbox') {
                value = input.checked;
            } else {
                value = input.value;
            }

            payload.attributes[key] = value;
        });

        fetch(`/admin/apps/${appAid}/custom-attributes/update`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Attributes successfully updated:', data.attributes);
                    // Handle success, close the modal, or refresh the table data
                    const dialog = document.getElementById(`edit-custom-attributes-${appAid}`);
                    dialog.classList.remove('show');
                } else {
                    console.error('Failed to update attributes:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Sort table based on column index
    function sortTable(table, columnIndex, isNumeric = false) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.sort((rowA, rowB) => {
            const cellA = rowA.children[columnIndex].innerText.trim();
            const cellB = rowB.children[columnIndex].innerText.trim();

            if (isNumeric) {
                return parseFloat(cellA) - parseFloat(cellB);
            }
            return cellA.localeCompare(cellB);
        });

        rows.forEach(row => tbody.appendChild(row)); // Append rows back in sorted order
    }

    // Initialize all event listeners
    initializeEventListeners();
});
