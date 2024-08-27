(function ($) {
  $(document).ready(function () {
    new Swiper('.image-box-carousel', {
      slidesPerView: 1,
      loop: true,
      spaceBetween: 0,
      breakpoints: {
        640: {
          slidesPerView: 1,
          spaceBetween: 0,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 30,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 60,
        },
      }
    });
  });
})(jQuery);