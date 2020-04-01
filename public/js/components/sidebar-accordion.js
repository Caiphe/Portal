window.onload = function() {
    var accordion = document.querySelectorAll('.accordion-item');
    var menu = document.querySelector('.accordion-menu');
    var i = 0;

    for (; i < accordion.length; i++) {
        var button = accordion[i].querySelector('a[data-toggle="accordion-item"]');
        var submenu = accordion[i].querySelector('.accordion-sub-items');

        if (accordion.length > 1) {
            //accordion open and close items
            button.addEventListener("click", toggleShow);
        }

        //add active class to sub-item onclick
        submenu.addEventListener("click", makeActive);
    }

    function toggleShow() {
        this.parentNode.classList.toggle('show');
    }

    function makeActive(event) {
        menu.querySelectorAll('.accordion-sub-items li a.active').forEach(function(item) {
            item.classList.remove('active');
        });

        event.target.classList.add('active');
    }
}
