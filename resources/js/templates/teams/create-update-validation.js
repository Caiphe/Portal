document.querySelector('#team-name').addEventListener('keyup', removeSpecialCharacters);
function removeSpecialCharacters(){
    var specialChrs = /[`~!)@#$%(^&*|+=?;:±§'",.<>\{\}\[\]\\\/]/gi;
    this.value = this.value.replace(/  +/g, ' ');

    if(specialChrs.test(this.value)){
        this.value = this.value.replace(specialChrs, '');
        addAlert('warning', 'Team name cannot contain special characters.');
    }
}

document.querySelector('#team-contact').addEventListener('keyup', validatePhoneNumber);
function validatePhoneNumber(){
    var specialChrs = /[a-z`~!)@#$%(^&*|=?;:±§'",.<>\{\}\[\]\\\/]/gi;
    var containsNonDigits = specialChrs.test(this.value);

    if(containsNonDigits){
        addAlert('warning', 'Character not allowed');
    }

    this.value = this.value.replace(specialChrs, "");
}


document.querySelector('#form-create-team').addEventListener('submit', createTeam);
function createTeam(event){
    var form = this.elements;
    var errors = [];
    var urlValue = form['url'].value;
    var contactNumber = form['contact'].value;
    var phoneRegex = /^\+|0(?:[0-9] ?){6,14}[0-9]$/;

    if(form['name'].value === ''){
        errors.push("Name required");
    }

    if(urlValue === ''){
        errors.push("Team URL required");
    } 
    
    if(urlValue !== '' && !/https?:\/\/.*\..*/.test(urlValue)) {
        errors.push('Please add a valid team url. Eg. https://url.com');
    }

    if(contactNumber === ''){
        errors.push("Contact number required");
    }
    
    if(contactNumber !== '' && !phoneRegex.test(contactNumber)){
        errors.push("Valid phone number required");
    }

    if(form['country'].value === ''){
        errors.push("Country required");
    }

    if (errors.length > 0) {
        event.preventDefault();
        return void addAlert('error', errors.join('<br>'));
    }

    return true;
}