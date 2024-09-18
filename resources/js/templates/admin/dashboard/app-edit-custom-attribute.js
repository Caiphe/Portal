document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let appAid = null;
    let tags = []; // Store tags for number type

    // Function to initialize event listeners
    function initializeEventListeners() {
        const container = document.querySelector('#table-data');

        container.addEventListener('click', function (event) {
            if (event.target.matches('.btn-show-edit-attribute-modal')) {
                appAid = event.target.getAttribute('data-edit-id');
                const editCustomAttributeModal = document.getElementById(`edit-custom-attributes-${appAid}`);

                if (editCustomAttributeModal) {
                    editCustomAttributeModal.classList.add('show');
                    fetchAttributes(appAid, editCustomAttributeModal); // Fetch and populate attributes
                } else {
                    console.error(`Modal with id custom-attributes-${appAid} not found`);
                }
            }
        });
    }

    // Fetch existing attributes and populate the modal
    function fetchAttributes(appAid, modal) {
        fetch(`/apps/${appAid}/custom-attributes`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateModal(modal, data.attributes);
                } else {
                    console.error('Error fetching attributes:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Populate the modal with pre-existing data
    function populateModal(modal, attributes) {
        const nameField = modal.querySelector('#name');
        const valueField = modal.querySelector('#value');
        const numberField = modal.querySelector('#number-value');
        const booleanField = modal.querySelector('#boolean-value');
        const typeSelect = modal.querySelector('#type');
        const submitButton = modal.querySelector('.btn-confirm');

        // Iterate over the flattened attributes
        for (const [name, value] of Object.entries(attributes)) {
            // Set the name field to the key (e.g. "vvv", "Country")
            nameField.value = name;

            // Set the value field based on the type of value
            if (typeof value === 'string') {
                // Simple key-value pair, use string type
                typeSelect.value = 'string';
                valueField.value = value;
                valueField.style.display = 'block';

            } else if (typeof value === 'boolean') {
                // Boolean value, set boolean type
                typeSelect.value = 'boolean';
                booleanField.checked = value;
                booleanField.style.display = 'block';

            } else if (Array.isArray(value)) {
                // Array type (e.g. CSV string array)
                typeSelect.value = 'csv string array';
                numberField.value = value.join(', '); // Assuming CSV array of values
                numberField.style.display = 'block';
                updateTagDisplay(modal, value);
            }
        }

        // Revalidate the form
        checkIfFormIsValid(modal, nameField, valueField, numberField, booleanField, [], submitButton);
    }

    initializeEventListeners();
});
