(function () {
    let profiles = adminAppsCreateLookup('userProfiles');
    let searchWrapper = document.querySelector(".search-input");
    let searchField = searchWrapper.querySelector(".search-field");
    let form = document.getElementById('create-app-form');
    let removeThumbnail = document.getElementById('remove-assignee');
    let appCreatorEmail = adminAppsCreateLookup('appCreatorEmail');
    let suggestions = adminAppsCreateLookup('userEmails');
    let ownerAvatar = document.querySelector(".owner-avatar");
    let suggBox = searchWrapper.querySelector(".autocom-box");

    searchField.addEventListener('keyup', searchFieldSuggestions);

    document.getElementById('assign-to-me').addEventListener('click', assignToMe);

    removeThumbnail.style.display = 'none';

    function assignToMe() {
        select(appCreatorEmail);
        removeThumbnail.style.display = '';
    }

    document.querySelector('#name').addEventListener('keyup', checkSpecialCharacters);
    function checkSpecialCharacters(){
        let specialChrs = /[`~!@#$%^&*|+=?;:±§'",.<>\{\}\[\]\\\/]/gi;
        if(specialChrs.test(this.value)){
            this.value = this.value.replace(specialChrs, '');
            addAlert('warning', 'Application name cannot contain special characters.');
        }
    }
    
    // if user press any key and release
    function searchFieldSuggestions(e) {
        let userData = e.target.value; //user enetered data
        let emptyArray = [];
        if (userData) {
            emptyArray = suggestions.filter((data) => {
                //filtering array value and user characters to lowercase and return only those words which are start with user enetered chars
                return data.toLocaleLowerCase().startsWith(userData.toLocaleLowerCase());
            });
            emptyArray = emptyArray.map((data) => {
                // passing return data inside li tag
                return data = `<li>${data}</li>`;
            });
            searchWrapper.classList.add("active"); //show autocomplete box
            showSuggestions(emptyArray);
            let allList = suggBox.querySelectorAll("li");


            for (const item of allList) {
                // adding onclick attribute in all li tag
                item.addEventListener('click', function () {
                    removeThumbnail.style.display = "inline-block";
            
                    select(this);
                });
            }
            
        } else {
            searchWrapper.classList.remove("active"); //hide autocomplete box
        }
    }

    function select(element) {
        let selectData = element.textContent || element;
        let creatorEmail = document.querySelector(".creator-email");

        searchField.value = selectData;
        creatorEmail.innerHTML = selectData;
        removeThumbnail.classList.add("active")
        searchWrapper.classList.remove("active");

        // To replace with the creator thumbnail
        ownerAvatar.style.backgroundImage = "url(" + profiles[selectData] + ")";

        removeThumbnail.addEventListener('click', function () {
            this.classList.remove("active");
            creatorEmail.innerHTML = 'No creator assigned';
            searchField.value = '';
            ownerAvatar.style.backgroundImage = "url(/images/app-profile.png)";
            this.style.display = 'none';
        });
    }

    function showSuggestions(list) {
        let listData;

        if (!list.length) {
            listData = `<li class="non-cursor"><div class="hide-cursor">No user found try again</div></li>`;
        } else {
            listData = list.join('');
        }
        suggBox.innerHTML = listData;
    }

    // custom attribute add
    let addAttributeBtn = document.querySelector('.add-attribute');
    let attributeName = document.querySelector('#attribute-name');
    let attributeValue = document.querySelector('#attribute-value');
    let attributeErrorMessage = document.querySelector('#attribute-error');
    let attributesList = document.querySelector('#custom-attributes-list');

    addAttributeBtn.addEventListener('click', addNewAttribute);

    attributeName.addEventListener('change', checkNameExists);
    
    attributeValue.addEventListener('change', validateAttributeValue);

    function addNewAttribute(){
        let attributeName = document.querySelector('#attribute-name');
        const attributeBlocks = document.querySelectorAll('.each-attribute-block');

        // Check if the number of attribute blocks is greater than 18
        if (attributeBlocks.length > 9) {
            attributeName.value = '';
            attributeValue.value = '';
            addAlert('warning', 'You have reached the limit of attributes.');
            return false; 
        }

        let elements = document.getElementById('create-app-form').elements;

        if(attributeName.value === "" || attributeValue.value === ''){
            attributeErrorMessage.classList.add('show');

            setTimeout(function(){
                attributeErrorMessage.classList.remove('show');
            }, 4000);

            return;
        }

        var customAttributeBlock = document.getElementById('custom-attribute').innerHTML;
        var addedAttributeForm = document.querySelector('.custom-attribute-list-container');

        customAttributeBlock = document.createRange().createContextualFragment(customAttributeBlock);
        customAttributeBlock.querySelector('.name').value = attributeName.value;
        customAttributeBlock.querySelector('.value').value = attributeValue.value;
        attributesList.appendChild(customAttributeBlock);
        attributeName.value = '';
        attributeValue.value = '';
        document.querySelector('.attributes-heading').classList.add('show');
        addedAttributeForm.classList.remove('non-active');
        addedAttributeForm.classList.add('active');

        var attrNames = elements['attribute[name][]'];
        var attrValues = elements['attribute[value][]'];

        if(attrNames && attrNames.length === undefined) {
            attrNames = [attrNames];
            attrValues = [attrValues];
        }

        if(attrNames){
            for(var i = 0; i < attrNames.length; i++){
                attrNames[i].addEventListener('change', checkNameExists);
            }
        }
    }

    function validateAttributeValue(){
        const valueMaxSize = 2048;

        this.value = this.value.replaceAll(/["']/g, "").replaceAll(/  +/g, '');

        if(this.value.length > valueMaxSize){
            addAlert('warning', `Attribute value cannot exceed ${valueMaxSize} characters.`);
            this.value = '';
            return;
        }
    }

    function checkNameExists(){
        if(this.value.length <= 1){
            addAlert('warning', 'Please provide a valid attribute name.');
            this.value = '';
            return;
        }

        const nameMaxSize = 1024;
        if(this.value.length > nameMaxSize){
            addAlert('warning', `Attribute name cannot exceed ${nameMaxSize} characters.`);
            this.value = '';
            return;
        }

        // Check if the value contains only numbers
        if (/^\d+$/.test(this.value)) {
            addAlert('warning', 'The attribute name cannot contain only numbers.');
            this.value = '';
            return;
        }

        if(this.value.includes(' ')){
            addAlert('warning', 'White spaces are not allowed to be used in attribute names and have been automatically removed.');
        }

        var pattern = new RegExp('[ ]+', 'g');
        this.value = this.value.replaceAll(/["']/g, "").replace(pattern, '');

        var elements = document.getElementById('create-app-form').elements;
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

        var existingNames = ['Location', 'Country', 'TeamName', 'Description', 'DisplayName', 'Notes', 'Channels', 'EntityName', 'ContactNumber'];
        for(var i = 0; i < existingNames.length; i++){
            if(existingNames[i].toLowerCase() === this.value.toLowerCase()){
                this.value = '';
                this.focus();
                addAlert('warning', `${existingNames[i]} is a reserved attribute name.`);
                break;
            }
        }

        // Removes extra spaces
        var pattern = new RegExp('[ ]+', 'g');
        this.value = this.value.replaceAll(/["']/g, "").replace(pattern, '');
    }
}());
