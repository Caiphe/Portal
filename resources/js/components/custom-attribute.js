function attributeRemove(button){
    var attribute = button.parentNode;
    var formContainer = attribute.parentNode;
    if(attribute.parentNode['remove-check']){
        attribute.parentNode['remove-check'].value = 1;
    }
    attribute.parentNode.removeChild(attribute);

    if(formContainer.querySelectorAll('.each-attribute-block').length === 0){
        document.querySelector('.attributes-heading').classList.remove('show');
        var listContainert = document.querySelector('.custom-attribute-list-container');
        if(listContainert) listContainert.classList.remove('active');
        formContainer.parentNode.previousElementSibling.classList.remove('show');
        return;
    }
}

function removeQuote(field){
    field.value = field.value.replaceAll(/["']/g, "").replaceAll(/  +/g, ' ');
}
