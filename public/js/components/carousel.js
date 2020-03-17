(function() {
    var carousels = document.querySelectorAll(".carousel");

    for (var i = carousels.length - 1; i >= 0; i--) {
        setupCarousel(carousels[i]);
        window.setTimeout(makeCarouselReady.bind(null, carousels[i]), 700);
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
        pagerItem.className = 'carousel-pager-item';
        pagerItem.dataset.index = index;

        return pagerItem;
    }

    function paginate(ev) {
        var parent = void 0;
        if (ev.target.dataset.index === undefined) return;

        parent = this.parentNode;
        parent.querySelector('.active').classList.remove('active');
        parent.querySelectorAll('.carousel-item')[ev.target.dataset.index].classList.add('active');
    }
}());