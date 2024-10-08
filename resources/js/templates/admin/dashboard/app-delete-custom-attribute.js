document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let appAid = null;
    let deleteCustomAttributeModal = null; // Declare modal reference outside

    // Function to initialize event listeners
    function initializeEventListeners() {
        const container = document.querySelector('#table-data'); // Use a common parent element

        container.addEventListener('click', function (event) {
            if (event.target.matches('.btn-delete-attribute-modal')) {
                appAid = event.target.getAttribute('data-delete-id');
                const attributeKey = event.target.getAttribute('data-attribute-key');
                const attributeValue = event.target.getAttribute('data-attribute-value');

                deleteCustomAttributeModal = document.getElementById(`delete-custom-attribute-${appAid}`);

                if (deleteCustomAttributeModal) {
                    // Show the modal
                    deleteCustomAttributeModal.classList.add('show');

                    // Set the attribute name and value in the modal
                    deleteCustomAttributeModal.querySelector('#delete-attribute-name').textContent = attributeKey;
                    deleteCustomAttributeModal.querySelector('#delete-attribute-value').textContent = attributeValue;

                    // Set the attribute key in the hidden input field for deletion
                    deleteCustomAttributeModal.querySelector('input[name="attribute_key"]').value = attributeKey;
                } else {
                    console.error(`Modal with id delete-custom-attribute-${appAid} not found`);
                }
            }
        });

        // Handle form submission for delete
        const deleteForms = document.querySelectorAll('.confirm-user-deletion-request-form');
        deleteForms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent form from submitting normally

                // Get the attribute key from the form
                const attributeKey = form.querySelector('input[name="attribute_key"]').value;

                // Perform the AJAX request to delete the attribute
                fetch(`/admin/apps/${appAid}/custom-attributes/delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ attribute_key: attributeKey }) // Send the attribute key to be deleted
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            addAlert("Custom Attribute deleted successfully");
                            // Attribute successfully deleted, hide the modal and update the table
                            if (deleteCustomAttributeModal) {
                                deleteCustomAttributeModal.classList.remove('show'); // Hide the modal
                            }

                            // Optionally remove the row from the table
                            const rowToRemove = document.querySelector(`a[data-delete-id="${appAid}"][data-attribute-key="${attributeKey}"]`).closest('tr');
                            if (rowToRemove) {
                                rowToRemove.remove();
                            }

                            addAlert(data.message);
                            console.log('Attribute deleted:', attributeKey);
                        } else {
                            addAlert(data.message);
                            console.error('Failed to delete attribute:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
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
