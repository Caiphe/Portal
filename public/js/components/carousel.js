(function(){
    var carousels = document.querySelectorAll(".carousel");

    for (var i = carousels.length - 1; i >= 0; i--) {
        setupCarousel(carousels[i]);
    }

    function setupCarousel(carousel) {
        var carouselItems = carousel.querySelectorAll('.carousel-item');

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

        return pager;
    }

    function buildPagerItem(index) {
        var pagerItem = document.createElement('span');
        pagerItem.className = 'carousel-pager-item';
        pagerItem.dataset.index = index;

        return pagerItem;
    }
}());