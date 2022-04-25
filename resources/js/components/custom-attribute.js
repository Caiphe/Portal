function attributeRemove(button){
    var attribute = button.parentNode;
    attribute.parentNode.removeChild(attribute);

    if(document.querySelectorAll('.each-attribute-block').length === 0){
        var addedAttributeForm =  document.querySelector('.custom-attribute-list-container');
        document.querySelector('.attributes-heading').classList.remove('show');
        addedAttributeForm.classList.remove('active');
        addedAttributeForm.classList.add('non-active');
    }
}
