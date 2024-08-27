(function ($) {
  $(document).ready(function () {
    new Swiper('.slider-images', {
      spaceBetween: 0,
      autoplay: {
        delay: 9500,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: '.button-next',
        prevEl: '.button-prev',
      },

      breakpoints: {
        360: {
          direction: 'horizontal',
        },
        768: {
          draggable: false,
          direction: 'vertical',
          touchRatio: 0,
        },
      },

      pagination: {
        el: '.swiper-fraction',
        type: 'fraction',
      },

      loop: true,
      loopedSlides: 1,
    });
  });
})(jQuery);