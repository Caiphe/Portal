(function () {
    var carousels = document.querySelectorAll(".carousel");
    var transitionTimer = {};

    for (var i = carousels.length - 1; i >= 0; i--) {
        if (+carousels[i].dataset.wait < (+carousels[i].dataset.duration * 1000)) {
            console.log('The carousel should not have a wait period less than the duration.');
            continue;
        }
        
        setupCarousel(carousels[i], i);

        if (carousels[i].dataset.autoScroll === 'false') continue;

        carousels[i].addEventListener('mouseenter', transitionStop);
        carousels[i].addEventListener('mouseleave', transitionStart);

        window.setTimeout(transitionCarousel.bind(null, carousels[i]), +carousels[i].dataset.wait)
    }    

    function makeCarouselReady(carousel) {
        carousel.classList.add('ready');
    }

    function setupCarousel(carousel, index) {
        var carouselItems = carousel.querySelectorAll('.carousel-item');
        var duration = carousel.dataset.duration + "s";

        carousel.dataset.name = 'carousel-' + index;

        carouselItems[0].classList.add('active');

        carousel.appendChild(buildPager(carouselItems));

        for (var i = carouselItems.length - 1; i >= 0; i--) {
            carouselItems[i].style.animationDuration = duration;
        }

        transitionTimer['carousel-' + index] = null;

        window.setTimeout(makeCarouselReady.bind(null, carousel), (+carousel.dataset.duration * 1000));
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
        parent.querySelectorAll('.active').forEach(function (el) {
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

        if (carouselActive.nextElementSibling.className === "carousel-pager") {
            carousel.querySelector('.carousel-item').classList.add('active');
            carousel.querySelector('.carousel-pager-item').classList.add('active');
        } else {
            carouselActive.nextElementSibling.classList.add('active');
            pagerActive.nextElementSibling.classList.add('active');
        }

        carouselActive = null;
        pagerActive = null;

        if (carousel.dataset.autoScroll === 'false') return;

        transitionTimer[carousel.dataset.name] = window.setTimeout(transitionCarousel.bind(null, carousel), +carousel.dataset.wait);
    }

    function transitionStop() {
        window.clearTimeout(transitionTimer[this.dataset.name]);
        transitionTimer[this.dataset.name] = null;
    }

    function transitionStart() {
        if (transitionTimer[this.dataset.name] === undefined || transitionTimer[this.dataset.name] !== null) return;

        transitionTimer[this.dataset.name] = window.setTimeout(transitionCarousel.bind(null, this), +this.dataset.wait);
    }
}());