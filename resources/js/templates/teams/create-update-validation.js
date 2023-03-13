document.querySelector('#team-name').addEventListener('keyup', removeSpecialCharacters);
function removeSpecialCharacters(){
    var specialChrs = /[`~!)@#$%(^&*|+=?;:±§'",.<>\{\}\[\]\\\/]/gi;
    this.value = this.value.replace(/  +/g, ' ');

    if(specialChrs.test(this.value)){
        this.value = this.value.replace(specialChrs, '');
        addAlert('warning', 'Team name cannot contain special characters.');
    }
}

document.querySelector('#form-create-team').addEventListener('submit', createTeam);
function createTeam(event){
    var form = this.elements;
    var errors = [];

    if(form['name'].value === ''){
        errors.push("Name required");
    }

    if(form['url'].value === ''){
        errors.push("Team URL required");
    }

    if(form['contact'].value === ''){
        errors.push("Contact number required");
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