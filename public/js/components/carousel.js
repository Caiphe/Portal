(function() {
    var carousels = document.querySelectorAll(".carousel");
    var transitionTimer = void 0;
    var transitionCarouselSpeed = 2000;

    for (var i = carousels.length - 1; i >= 0; i--) {
        setupCarousel(carousels[i]);
        carousels[i].addEventListener('mouseenter', stopTransition);
        carousels[i].addEventListener('mouseleave', startTransition);
        window.setTimeout(makeCarouselReady.bind(null, carousels[i]), 300);
        window.setTimeout(transitionCarousel.bind(null, carousels[i]), transitionCarouselSpeed)
    }    

    function makeCarouselReady(carousel) {
        carousel.classList.add('ready');
    }

    function setupCarousel(carousel) {
        var carouselItems = carousel.querySelectorAll('.carousel-item');

        carouselItems[0].classList.add('active');

        carousel.appendChild(buildPager(carouselItems));
    }

    function buildPager(carouselItems) {
        var pager = document.createElement('div');
        var pagerFragment = document.createDocumentFragment();
        var i = 0;

        pager.className = "carousel-pager";

        for (; i < carouselItems.length; i++) {
            pagerFragment.appendChild(buildPagerItem(i));
        }

        pager.appendChild(pagerFragment);

        pager.addEventListener('click', paginate);

        return pager;
    }

    function buildPagerItem(index) {
        var pagerItem = document.createElement('button');
        pagerItem.className = 'carousel-pager-item pager-' + index + (index === 0 ? ' active' : '');
        pagerItem.dataset.index = index;

        return pagerItem;
    }

    function paginate(ev) {
        var parent = void 0;
        if (ev.target.dataset.index === undefined) return;

        parent = this.parentNode;
        parent.querySelectorAll('.active').forEach(function(el){
            el.classList.remove('active');
        });
        parent.querySelectorAll('.carousel-item')[ev.target.dataset.index].classList.add('active');
        parent.querySelector('.carousel-pager-item.pager-' + ev.target.dataset.index).classList.add('active');
    }

    function transitionCarousel(carousel) {
        var carouselActive = carousel.querySelector('.carousel-item.active');
        var pagerActive = carousel.querySelector('.carousel-pager-item.active');

        carouselActive.classList.remove('active');
        pagerActive.classList.remove('active');

        if(carouselActive.nextElementSibling.className === "carousel-pager"){
            carousel.querySelector('.carousel-item').classList.add('active');
            carousel.querySelector('.carousel-pager-item').classList.add('active');
        } else {
            carouselActive.nextElementSibling.classList.add('active');
            pagerActive.nextElementSibling.classList.add('active');
        }

        transitionTimer = window.setTimeout(transitionCarousel.bind(null, carousel), transitionCarouselSpeed);
    }

    function stopTransition(ev) {
        window.clearTimeout(transitionTimer);
        transitionTimer = null;
    }

    function startTransition(ev) {
        transitionTimer = window.setTimeout(transitionCarousel.bind(null, this), transitionCarouselSpeed);
    }
}());