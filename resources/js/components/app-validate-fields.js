(function () {
    // Validation phone number 
    let contact_number = document.getElementById('contact_number');
    contact_number.addEventListener('input', validateOnInput);
    let errorContact = document.getElementById('contact_number_error');

    function validateOnInput() {
        // Sanitize input: allow only digits, spaces, parentheses, hyphens, and plus signs
        this.value = this.value.replace(/[^+\d\s\-()]/g, '');
    
        // Check if the contact number is valid
        if (!validateContactNumber(this.value)) {
            errorContact.classList.add('show');
            errorContact.innerText = 'Valid Contact Number required.';
        } 
        // Check if the length exceeds 20 characters
        else if (this.value.length > 20) {
            errorContact.classList.add('show');
            errorContact.innerText = 'Contact Number is too long.';
            this.value = this.value.slice(0, 20); // Truncate if necessary
        } 
        // Hide error if input is valid and within length
        else {
            errorContact.classList.remove('show');
            errorContact.innerText = '';
        }
    }

    // COuntry validtion
    let country = document.getElementById('country');
    let errorCountry = document.getElementById('country_error');
    country.addEventListener('change', validateCountry);
    function validateCountry(){
        if(country.value === ''){
            errorCountry.classList.add('show');
            errorCountry.innerText = 'Select a country to continue.';
        }else{
            errorCountry.classList.remove('show');
            errorCountry.innerText = '';
        }
    }

    // Channel validation
    let channel = document.getElementById('channel_error');
    let channels = document.querySelectorAll('input[name="channels[]"]');
    channels.forEach((channel) => {channel.addEventListener('change', validateChannel)});
    function validateChannel(){
        if(!validate.checked('channels', 1)){
            channel.classList.add('show');
            channel.innerText = 'Select at least one channel to continue.';
        }else{
            channel.classList.remove('show');
            channel.innerText = '';
        }
    }

    // validate entity name 
    let entity_name = document.getElementById('entity_name');
    let entity_name_error = document.getElementById('entity_name_error');
    entity_name.addEventListener('input', validateEntityName);
    function validateEntityName(){
        if(entity_name.value === ''){
            entity_name_error.classList.add('show');
            entity_name_error.innerText = 'Entity name is required.';
        }else{
            entity_name_error.classList.remove('show');
            entity_name_error.innerText = '';
        }
    }

    // validate products
    let productError = document.getElementById('product_error');
    let productsList = document.querySelectorAll('input[name="add_product[]"]');
    productsList.forEach((product) => {product.addEventListener('change', validateProduct)});
    function validateProduct(){
        if(!validate.checked('add_product', 1)){
            productError.classList.add('show');
            productError.innerText = 'Select at least one product to continue.';
            productError.style.marginBottom = '10px';
            
        }else{
            productError.classList.remove('show');
            productError.innerText = '';
        }
    }

    // Function to validate contact number format
    function validateContactNumber(number) {
        const contactNumberRegex = /^\+?[\d\s\-()]{7,20}$/;
        return contactNumberRegex.test(number);
    }


        /* Validate Form Elements As User Interacts */
        const validate = {
            showError(elementID, errorResponseText) {
                const e = document.getElementById(elementID);
                e.innerText = errorResponseText;
                e.style.visibility = 'visible';
                e.style.display = 'block';
            },
    
            hideError(elementID) {
                const e = document.getElementById(elementID);
                e.style.visibility = 'hidden';
                e.style.display = 'none';
            },
    
            /**
             * This checks if the application name has a valid state. There is other code which
             * checks and sets validity, but this one manages the programmatic check where needed.
             * @param elementID
             * @return boolean
             */
            checkAppName(elementID) {
                const e = document.getElementById(elementID);
                const eValidationState = e.dataset.validationState;
                return eValidationState === "valid";
            },
    
            /**
             * This very simply checks if a field has been completed, because it is required.
             * The value itself is not checked.
             * @param elementID
             * @return boolean
             */
            required(elementID) {
                const e = document.getElementById(elementID);
                return e.value !== '';
            },
    
            /**
             * This checks that the given form element array of checkboxes has a specific number of checkboxes
             * selected.
             */
            checked(inputName, min) {
                const checkboxes = document.querySelectorAll('input[name="' + inputName + '[]"]:checked');
                return checkboxes.length >= min;
            },
        }
}());