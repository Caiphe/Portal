(function () {

    var accordion = document.querySelectorAll('.accordion-item');
    var accordionArray = Array.prototype.slice.call(accordion, 0);
    accordionArray.forEach(function (el) {
        var button = el.querySelector('a[data-toggle="accordion-item"]'),
            submenu = el.querySelector('.accordion-sub-items'),
            arrow = button.querySelector('i.icon-arrow');
        
            button.onclick = function (event) {
                if (!submenu.hasClass('show')) {
                    submenu.classList.add('show');
                    submenu.classList.remove('hide');
                    arrow.classList.add('open');
                    arrow.classList.remove('close');
                    event.preventDefault();
                }
                else {
                    submenu.classList.remove('show');
                    submenu.classList.add('hide');
                    arrow.classList.remove('open');
                    arrow.classList.add('close');
                    event.preventDefault();
                }
            };
    })
    
    Element.prototype.hasClass = function (className) {
        return this.className && new RegExp("(^|\\s)" + className + "(\\s|$)").test(this.className);
    };

}());