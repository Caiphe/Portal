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
