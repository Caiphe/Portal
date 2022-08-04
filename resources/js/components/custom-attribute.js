function attributeRemove(button){
    var attribute = button.parentNode;
    var formContainer = attribute.parentNode;
    if(attribute.parentNode['remove-check']){
        attribute.parentNode['remove-check'].value = 1;
    }
    attribute.parentNode.removeChild(attribute);

    if(formContainer.querySelectorAll('.each-attribute-block').length === 0){
        var addedAttributeForm =  document.querySelector('.custom-attributes-list');
        document.querySelector('.attributes-heading').classList.remove('show');
        addedAttributeForm.classList.remove('active');
        addedAttributeForm.classList.add('non-active');
        var listContainert = document.querySelector('.custom-attribute-list-container');
        if(listContainert) listContainert.classList.remove('active');
    }
}
