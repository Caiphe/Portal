(function () {

    var accordion = document.querySelectorAll('.accordion-item');
    var accordionArray = Array.prototype.slice.call(accordion, 0);
    accordionArray.forEach(function (el) {
        var button = el.querySelector('a[data-toggle="accordion-item"]'),
            submenu = el.querySelector('.accordion-sub-items'),
            arrow = button.querySelector('i.icon-arrow');
        
        //accordion open and close items
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
        
        //add active class to sub-item onclick
        var menu = document.querySelector('.accordion-menu');
        submenu.onclick = function (event) {            
            var submitemActive = menu.querySelectorAll('.accordion-sub-items li a.active');
            if (submitemActive.length > 0) {
                submitemActive.forEach(function (item) {
                    item.classList.remove('active');
                });
            }
            event.target.classList.add('active');
        }
    })
    
    Element.prototype.hasClass = function (className) {
        return this.className && new RegExp("(^|\\s)" + className + "(\\s|$)").test(this.className);
    };

}());