(function () {
    function init() {
        var toggleAppEls = document.querySelectorAll('.toggle-app');
        var renewCredentials = document.querySelectorAll('.renew-credentials');
        var environment = document.querySelectorAll('.environment');
        var logNotes = document.querySelectorAll('.log-notes');
        var customAttributes = document.querySelectorAll('.custom-attributes');
        var productStatusButtons = document.querySelectorAll('.product-status-action');
        var appStatusAction = document.querySelectorAll('.app-status-action');
        var productAction = document.querySelectorAll(".product-action");
        var kycStatus = document.querySelectorAll(".kyc-status-select");

        for (var i = toggleAppEls.length - 1; i >= 0; i--) {
            toggleAppEls[i].addEventListener('click', toggleApp);
        }

        for (var i = renewCredentials.length - 1; i >= 0; i--) {
            renewCredentials[i].addEventListener('submit', confirmRenewCredentials);
        }

        for (var i = environment.length - 1; i >= 0; i--) {
            environment[i].addEventListener('click', switchEnvironment);
        }

        for (var m = 0; m < logNotes.length; m++) {
            logNotes[m].addEventListener('click', viewNote);
        }

        for (var i = 0; i < customAttributes.length; i++) {
            customAttributes[i].addEventListener('click', fetchAttributes);
        }

        for (var m = 0; m < productStatusButtons.length; m++) {
            productStatusButtons[m].addEventListener('click', showProductNoteDialog);
        }

        for (var i = appStatusAction.length - 1; i >= 0; i--) {
            appStatusAction[i].addEventListener('click', updateAppStatus);
        }

        for (var i = productAction.length - 1; i >= 0; i--) {
            productAction[i].addEventListener('click', showProductActions);
        }

        for (var i = kycStatus.length - 1; i >= 0; i--) {
            kycStatus[i].addEventListener('change', handleKycUpdateStatus);
        }
    }

    init();
    ajaxifyOnPopState = updateFilters;
    ajaxifyComplete.push(init);

    function updateFilters(params) {
        document.getElementById('search-page').value = params['q'] || '';
        document.getElementById('product-filter-status').value = params['product-status'] || '';
        document.getElementById('filter-country').value = params['countries'] || '';
    }

    function toggleApp() {
        this.closest('.app').classList.toggle('show');
    }

    function confirmRenewCredentials() {
        if (!confirm('Renewing the credentials will revoke the current credentials, do you want to continue?')) return;

        addLoading('Renewing credentials...');
    }

    function switchEnvironment() {
        this.closest('.detail').className = 'detail active-' + this.dataset.environment;
    }

    function viewNote(e) {
        var id = this.dataset.id;
        var noteDialog = document.getElementById('admin-' + id);

        if (!noteDialog) return;

        noteDialog.classList.add('show');
    }


    function fetchAttributes(){
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var id = this.dataset.id;
        var url = this.dataset.route;

        var app = {
            _method: 'PUT',
            _token: token,
            aid: id
        };

        var xhr = new XMLHttpRequest();

        addLoading('Fetching custom attributes...');

        xhr.open('POST', url, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', token);
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.send(JSON.stringify(app));

        xhr.onload = function() {
            removeLoading();
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
            var attributeDialog = document.getElementById(`custom-attributes-${result['id']}`);

            if (xhr.status === 200) {
                document.querySelector(`#custom-attributes-list-partial-${result['id']}`).innerHTML = result['listHtml'];
                document.querySelector(`#custom-attributes-form-partial-${result['id']}`).innerHTML = result['formHtml'];

                var eachAttributeBlock = attributeDialog.querySelector('.each-attribute-block');
                if(eachAttributeBlock){
                    attributeDialog.querySelector('.attributes-heading').classList.add('show');
                }

                customAttributesDialog(result['id']);
                return;

            } else {

                if(result.errors) {
                    result.message = [];
                    for(var error in result.errors){
                        result.message.push(result.errors[error]);
                    }
                }

                addAlert('error', result.message || 'Sorry there was a problem updating your app. Please try again.');
            }
        };
    }

    function customAttributesDialog(id){
        var customAttributeDialog = document.getElementById('custom-attributes-' + id);
        if (!customAttributeDialog) return;
        customAttributeDialog.classList.add('show');
        var addAttributeBtn = customAttributeDialog.querySelector('.add-attribute');
        var attributesList = customAttributeDialog.querySelector('.custom-attributes-form-list');
        var currentAttributeName = customAttributeDialog.querySelector('.attribute-name');
        var currentAttributevalue = customAttributeDialog.querySelector('.attribute-value');

        var elements = attributesList.elements;
        var attrNames = elements['attribute[name][]'];

        if(attrNames && attrNames.length === undefined) {
            attrNames = [attrNames];
        }

        if(attrNames){
            for(var i = 0; i < attrNames.length; i++){
                attrNames[i].addEventListener('change', checkNameExists.bind(attrNames[i], id));
            }
        }

        currentAttributeName.addEventListener('change', checkNameExists.bind(currentAttributeName, id));

        currentAttributevalue.addEventListener('change', removeQuote.bind(currentAttributevalue));

        addAttributeBtn.addEventListener('click', addNewAttribute.bind(customAttributeDialog, id));

        customAttributeDialog.addEventListener('dialog-closed', submitNewAttribute.bind(attributesList, id));
    }

    function checkNameExists(id){
        if(this.value.length <= 1){
            addAlert('warning', 'Please provide a valid attribute name.');
            this.value = '';
            return;
        }

        if(this.value.includes(' ')){
            addAlert('warning', 'White spaces are not allowed to be used in attribute names and have been automatically removed.');
        }

        var pattern = new RegExp('[ ]+', 'g');
        this.value = this.value.replaceAll(/["']/g, "").replace(pattern, '');

        var existingNames = ['Location', 'Country', 'TeamName', 'Description', 'DisplayName', 'Notes'];

        for(var i = 0; i < existingNames.length; i++){
            if(existingNames[i].toLowerCase() === this.value.toLowerCase()){
                this.value = '';
                addAlert('warning', `${existingNames[i]} is a reserved attribute name.`);
                break;
            }
        }

        var customAttributeDialog = document.getElementById('custom-attributes-' + id);
        var elements = customAttributeDialog.querySelector('.custom-attributes-form-list').elements;

        var attrNames = elements['attribute[name][]'];

        if(attrNames && attrNames.length === undefined) {
            attrNames = [attrNames];
        }

        if(attrNames){
            for(var i = 0; i < attrNames.length; i++){
                if(attrNames[i] !== this && attrNames[i].value.toLowerCase() === this.value.toLowerCase()){
                    this.value = '';
                    this.focus();
                    addAlert('warning', 'Attribute name exists already.');
                    break;
                }
            }
        }

        var pattern = new RegExp('[ ]+', 'g');
        this.value = this.value.replaceAll(/["']/g, "").replace(pattern, '');
    }

    function submitNewAttribute(id){

        var elements = this.elements;
        var attrNames = elements['attribute[name][]'];
        var attrValues = elements['attribute[value][]'];
        var removeCheck = elements['remove-check'].value;
        var attributeData = this.querySelectorAll('.attribute-data');
        var attributesUpdated = false;

        for(var i = 0; i < attributeData.length; i++){
            if(attributeData[i].value !== attributeData[i].dataset.original){
                attributesUpdated = true;
                break;
            }
        }

        if(!attributesUpdated && removeCheck !== "1"){
            return;
        }

        var app = {
            attribute: [],
            _method: 'PUT',
            _token: elements['_token'].value,
        };

        if(attrNames && attrNames.length === undefined) {
            attrNames = [attrNames];
            attrValues = [attrValues];
        }

        if(attrNames){
            for(var i = 0; i < attrNames.length; i++) {

                if(attrValues[i].value === '' || attrNames[i].value === ''){
                    addAlert('error', 'Custom attributes were not updated because of an empty name or value. Try again.');
                    return;
                }

                app.attribute.push({
                    'name': attrNames[i].value,
                    'value': attrValues[i].value
                });
            }
        }

        var xhr = new XMLHttpRequest();

        addLoading('Updating...');

        xhr.open('POST', this.action, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', elements['_token'].value);
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.send(JSON.stringify(app));

        xhr.onload = function() {
            removeLoading();
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            if (xhr.status === 200) {
                const attributes = Object.values(result['attributes']);
                elements['remove-check'].value = '';

                if (attributes.length < 1) {
                    document.querySelector('#wrapper-' + id + ' .list-custom-attributes').innerHTML = '<div class="no-custom-attribute">None defined</div>';
                    addAlert('success', ['Custom attributes removed successfully']);
                } else {
                    updateAppAttributesHtml(result['attributes'], id);
                    addAlert('success', ['Custom attributes added successfully']);
                }

            } else {
                if(result.errors) {
                    result.message = [];
                    for(var error in result.errors){
                        result.message.push(result.errors[error]);
                    }
                    return;
                }

                addAlert('error', result.message || 'Sorry there was a problem updating your app. Please try again.');
            }
        };
    }

    function updateAppAttributesHtml(attributes, id){
        var attrHtml = '';
        for (key in attributes) {
            if(key !== 'Notes' && attributes[key] !== ''){
                attrHtml += `
                <div class="attribute-display">
                    <span class="attr-name bold">${key} : </span>
                    <span class="attr-value">${attributes[key]}</span>
                </div>
                `;
            }
        }
        document.querySelector('#wrapper-'+id+' .list-custom-attributes').innerHTML = attrHtml;
    }

    function addNewAttribute(id) {
        var attributeName = this.querySelector('.attribute-name');
        var attributeValue = this.querySelector('.attribute-value');
        var customAttributeDialog = document.getElementById('custom-attributes-' + id);
        var attributesList = customAttributeDialog.querySelector('.custom-attributes-form-list');

        var maxNameLength = 1024; // 1KB
        var maxValueLength = 2048; // 2KB

        // Validate if attributeName or attributeValue are empty
        if (attributeName.value === "" || attributeValue.value === '') {
            addAlert('error', 'Attribute name and value cannot be empty.');
            return;
        }

        // Validate the length of attributeName
        if (attributeName.value.length > maxNameLength) {
            addAlert('error', 'Attribute name cannot exceed 1KB (1024 characters).');
            return;
        }

        // Validate the length of attributeValue
        if (attributeValue.value.length > maxValueLength) {
            addAlert('error', 'Attribute value cannot exceed 2KB (2048 characters).');
            return;
        }

        // Check if attributes list has reached the limit of 18
        if (attributesList.childElementCount >= 19) {
            addAlert('error', 'Attributes name and value cannot exceed 18 attributes.');
            return;
        }

        attributesList.classList.add('active');
        var customAttributeBlock = document.getElementById('custom-attribute').innerHTML;
        customAttributeBlock = document.createRange().createContextualFragment(customAttributeBlock);
        customAttributeBlock.querySelector('.name').value = attributeName.value;
        customAttributeBlock.querySelector('.value').value = attributeValue.value;
        customAttributeDialog.querySelector('.attributes-heading').classList.add('show');
        attributesList.appendChild(customAttributeBlock);
        attributeName.value = '';
        attributeValue.value = '';
    }

    function removeQuote(){
        this.value = this.value.replaceAll(/["']/g, "").replaceAll(/  +/g, '');
        return;
    }

    function showProductNoteDialog() {
        var dialog = document.getElementById('status-dialog');
        var productStatusAction = this;
        var actionSibling = { revoke: 'approve', approve: 'revoke' }[productStatusAction.dataset.action] || '';
        var productStatusActionSibling = productStatusAction.parentNode.querySelector('.product-' + actionSibling);

        productStatusAction.disabled = true;
        productStatusActionSibling.disabled = true;

        dialog.querySelector('.app-dialog-heading').innerHTML = '<em>' + this.dataset.productDisplayName + '</em> note:';

        dialog.addEventListener('dialog-closed', function () {
            if (productStatusAction) productStatusAction.disabled = false;
            if (productStatusActionSibling) productStatusActionSibling.disabled = false;

            this.querySelector('.status-dialog-form').removeEventListener('submit', submitStatusDialog, { once: true });

            dialog = null;
            productStatusAction = null;
            productStatusActionSibling = null;
        }, { once: true });

        dialog.querySelector('.status-dialog-form').addEventListener('submit', submitStatusDialog, { once: true });

        function submitStatusDialog(ev) {
            var product = productStatusAction.parentNode;
            ev.preventDefault();

            document.getElementById('status-dialog').classList.remove('show');

            updateProductStatus({
                action: productStatusAction.dataset.action,
                for: productStatusAction.dataset.for,
                app: product.dataset.aid,
                product: product.dataset.pid,
                productSlug: product.dataset.productSlug,
                displayName: product.dataset.productDisplayName,
                statusNote: this.elements['status-note'].value
            }, product);

            dialog.querySelector('.status-dialog-textarea').value = '';

            dialog = null;
            productStatusAction = null;
        }

        dialog.classList.add('show');
    }

    function updateProductStatus(data, product) {
        var xhr = new XMLHttpRequest();
        var lookup = {
            approve: 'approved',
            revoke: 'revoked'
        };

        addLoading('Updating status...');

        xhr.open('POST', '/admin/apps/' + data.product + '/' + data.action);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.getElementsByName("csrf-token")[0].content);
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.send(JSON.stringify(data));

        xhr.onload = function () {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;
            var productActionButtons = product.querySelectorAll('.product-status-action');
            var noteDialogContent;
            var ariaLabel = '';
            var datasetPending = 0;

            removeLoading();

            for (var i = productActionButtons.length - 1; i >= 0; i--) {
                productActionButtons[i].disabled = false;
            }

            if (xhr.status === 200) {
                noteDialogContent = document.querySelector('#admin-' + data.app + data.productSlug + ' .note');
                noteDialogContent.innerHTML = result.body;
                ariaLabel = product.closest('.app').querySelector('.app-status');

                product.className = 'product product-status-' + lookup[data.action];
                datasetPending = Math.max(+ariaLabel.dataset.pending - 1, 0);
                ariaLabel.setAttribute('aria-label', datasetPending + ' Pending products');
                ariaLabel.dataset.pending = datasetPending;
                addAlert('success', '<strong>Product ' + lookup[data.action] + '.</strong> The product <em>' + data.displayName + '</em> has been ' + lookup[data.action]);
            } else {
                addAlert('error', result.body || 'There was an error updating the product.');
            }

            product = null;
        };
    }

    function updateAppStatus() {
        var dialog = document.getElementById('status-dialog');
        var dialogForm = dialog.querySelector('.status-dialog-form');

        dialogForm.action = this.dataset.action;
        dialogForm.addEventListener('submit', function () {
            dialog.classList.remove('show');
            addLoading('Updating status...');
        }, {
            once: true
        });

        dialog.querySelector('.app-dialog-heading').innerHTML = '<em>' + this.dataset.appDisplayName + '</em> note:';
        dialog.querySelector('.app-dialog-status').value = this.dataset.status;
        dialog.classList.add('show');
    }

    function handleKycUpdateStatus() {
        var xhr = new XMLHttpRequest();
        var kycSelect = this;

        xhr.open('POST', '/admin/apps/' + kycSelect.dataset.aid + '/kyc-status');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.getElementsByName("csrf-token")[0].content);
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        addLoading('Updating KYC status');

        xhr.send(JSON.stringify({
            kyc_status: kycSelect.value
        }));

        xhr.onload = function () {
            var result = xhr.responseText ? JSON.parse(xhr.responseText) : null;

            removeLoading();

            if (xhr.status === 200) {
                addAlert('success', result.body || 'Success.');
            } else {
                addAlert('error', result.body || 'There was an error updating the product.');
            }
        };
    }

    function showProductActions() {
        this.parentNode.classList.toggle('show-actions');
    }

}());

