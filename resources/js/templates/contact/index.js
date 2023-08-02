document.querySelector('#contact-form').addEventListener('submit', submitContact);
function submitContact(ev){
    
    var form = this.elements;
    var errors = [];
    var mailformat = /^[\w\.\-\+]+@[\w\.\-]+\.[a-z]{2,5}$/;

    if(form['first_name'].value === ''){
        errors.push("First name required");
    }

    if(form['last_name'].value === ''){
        errors.push("Last name required");
    }

    if(form['email'].value === '' || !form['email'].value.match(mailformat)){
        errors.push("Valid email required");
    }

    if(form['message'].value === ''){
        errors.push("Message required");
    }

    if (errors.length > 0) {
        ev.preventDefault();
        return void addAlert('error', errors.join('<br>'));
    }

    return true;
}
