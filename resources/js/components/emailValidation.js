document.querySelector('#email').addEventListener('keyup', removeSpecialCharacters);
function removeSpecialCharacters(){
    var email = this.value.trim();
    
    if (email.startsWith(".")) {
        this.value = this.value.replace('.', '');
        addAlert('warning', 'First character cannot be a dot.');
    }

    if (email.startsWith("@")) {
        this.value = this.value.replace('@', '');
        addAlert('warning', 'First character cannot be at symbol.');
    }

    setTimeout(function(){
        var emailValue = document.querySelector('#email').value;
        if (emailValue.endsWith(".")) {
            emailValue = emailValue.slice(0, -1);
            addAlert('warning', 'Last character cannot be a dot.');
        }
    }, 2000)

    setTimeout(function(){
        var emailValue = document.querySelector('#email').value;
        if (emailValue.endsWith("@")) {
            emailValue = emailValue.slice(0, -1);
            addAlert('warning', 'Last character cannot be at symbol.');
        }
    }, 2000)
    
    if (email.includes('..')) {
        this.value = this.value.replace('..', '.');
        addAlert('warning', 'Consecutive dots not allowed.');
    }

    if (email.includes('@@')) {
        this.value = this.value.replace('@@', '@');
        addAlert('warning', 'Consecutive at symbols not allowed.');
    }

    var specialChrs = /[`~!)#$%(^&*|=?;:±§'",<>\{\}\[\]\\\/]/gi;
    this.value = this.value.replace(/  +/g, ' ');
    if(specialChrs.test(this.value)){
        this.value = this.value.replace(specialChrs, '');
        addAlert('warning', 'Email cannot contain special characters.');
    }
}
