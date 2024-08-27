(function ($) {
  $(document).ready(function () {
    "use strict";


    // BACK BUTTON RELOAD
    window.onpageshow = function (event) {
      if (event.persisted) {
        window.location.reload()
      }
    };

    /* HAMBURGER */
    var $burger = $('.hamburger')
    var $nav = $(".side-widget.-nav")
    if ($burger !== null) {
      $burger.on('click', function (e) {
        e.stopPropagation()
        $burger.toggleClass("active")
        $nav.toggleClass("active")
        $(".section-wrapper").toggleClass("no-transform")

        if ($burger.hasClass('active')) {
          $(document).on('click.hamburgerClose', function(event) {
            if (!$(event.target).closest('.side-widget.-nav, .hamburger').length) {
              closeHamburgerMenu();
            }
          });
        } else {
          $(document).off('click.hamburgerClose');
        }

        function closeHamburgerMenu() {
          $burger.removeClass("active");
          $nav.removeClass("active");
          $(".section-wrapper").removeClass("no-transform");
          $(document).off('click.hamburgerClose'); // Unbind the listener
        }
      });
    }

    /* DROPDOWN MENU HOVER */
    $('.dropdown').hover(function() {
      $(this).addClass('show');
      $(this).find('.dropdown-menu').addClass('show');
    }, function() {
      $(this).removeClass('show');
      $(this).find('.dropdown-menu').removeClass('show');
    });

    /* SEARCH */
    $('.search-button').on('click', function () {
      $(".search-box").toggleClass("active")
      $(".section-wrapper").toggleClass("no-transform")
    })

    // ACCORDION
    var allPanels = $('.accordion > dd').hide();
    $('.accordion > dt > a').click(function () {
      var panel = $(this).parent().next();
      panel.slideToggle();
      setTimeout(function() {
        locoScroll.update();
      }, 400);
      return false;
    });


    // PAGE TRANSITION
    // $('body a').on('click', function (e) {

    //   var target = $(this).attr('target');
    //   var fancybox = $(this).data('fancybox');
    //   var url = this.getAttribute("href");
    //   if (target != '_blank' && typeof fancybox == 'undefined' && url.indexOf('#') < 0) {


    //     e.preventDefault();
    //     var url = this.getAttribute("href");
    //     if (url.indexOf('#') != -1) {
    //       var hash = url.substring(url.indexOf('#'));


    //       if ($('body ' + hash).length != 0) {
    //         $('.page-transition').removeClass("active");


    //       }
    //     } else {
    //       $('.page-transition').toggleClass("active");
    //       setTimeout(function () {
    //         window.location = url;
    //       }, 1000);

    //     }
    //   }
    // });

    $(window).on('ajaxInvalidField', function(event, fieldElement, fieldName, errorMsg, isFirst) {
      $(fieldElement).closest('.inner').addClass('has-error');
    });

    $(document).on('ajaxPromise', '[data-request]', function() {
      $(this).closest('form').find('.inner.has-error').removeClass('has-error');
    });


    //TAB
    $(".tab-nav li").on('click', function (e) {
      $(".tab-item").hide();
      $(".tab-nav li").removeClass('active');
      $(this).addClass("active");
      var selected_tab = $(this).find("a").attr("href");
      $(selected_tab).stop().show();
      return false;
    });


  });
  // END DOCUMENT READY

  // DATA BACKGROUND IMAGE
  var pageSection = $("*");
  pageSection.each(function (indx) {
    if ($(this).attr("data-background")) {
      $(this).css("background", "url(" + $(this).data("background") + ")");
    }
    if ($(this).attr("data-background-fixed") == '1') {
      $(this).css("background-", "url(" + $(this).data("background") + ")");
    }
  });

  // DATA BACKGROUND COLOR
  var pageSection = $("*");
  pageSection.each(function (indx) {
    if ($(this).attr("data-background")) {
      $(this).css("background", $(this).data("background"));
    }
  });


  //IMAGE BOX CAROUSEL
  var swiper = new Swiper('.image-box-carousel', {
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


  // HOME INTRO SLIDER
  var sliderimages = new Swiper('.slider-images', {
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
        // draggable: true,
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
    // thumbs: {
    //   swiper: slidertexts
    // }
  });


  //SLIDER THUMBS
  // var slidertexts = new Swiper('.slider-texts', {
  //   spaceBetween: 10,
  //   centeredSlides: true,
  //   slidesPerView: 1,
  //   touchRatio: 0,
  //   slideToClickedSlide: false,
  //   loop: true,
  //   loopedSlides: 1,

  //   pagination: {
  //     el: '.swiper-pagination',
  //     type: 'progressbar',
  //   },

  // });

  // if ($(".slider-images")[0]) {
  //   sliderimages.controller.control = slidertexts;
  //   slidertexts.controller.control = sliderimages;
  // } else {

  // }


  // PRELOADER
  // let settings = {
  //   progressSize: 320,
  //   progressColor: '#ffffff',
  //   lineWidth: 2,
  //   lineCap: 'round',
  //   preloaderAnimationDuration: 800,
  //   startDegree: -90,
  //   finalDegree: 270
  // }

  // function setAttributes(elem, attrs) {

  //   for (let key in attrs) {
  //     elem.setAttribute(key, attrs[key]);
  //   }

  // }

  // let preloader = document.createElement('div'),
  //   canvas = document.createElement('canvas'),
  //   size;

  // (function () {

  //   let width = window.innerWidth,
  //     height = window.innerHeight;

  //   if (width > height) {

  //     size = Math.min(settings.progressSize, height / 2);

  //   } else {

  //     size = Math.min(settings.progressSize, width - 50);

  //   }

  // })();

  // setAttributes(preloader, {
  //   class: "preloader",
  //   id: 'preloader',
  //   style: 'transition: opacity ' + settings.preloaderAnimationDuration / 1000 + 's'
  // });
  // setAttributes(canvas, {
  //   class: 'progress-bar',
  //   id: 'progress-bar',
  //   width: settings.progressSize,
  //   height: settings.progressSize
  // });


  // preloader = document.getElementById('preloader');

  // let progressBar = document.getElementById('progress-bar'),
  //   images = document.images,
  //   imagesAmount = images.length,
  //   imagesLoaded = 0,
  //   barCtx = progressBar.getContext('2d'),
  //   circleCenterX = progressBar.width / 2,
  //   circleCenterY = progressBar.height / 2,
  //   circleRadius = circleCenterX - settings.lineWidth,
  //   degreesPerPercent = 3.6,
  //   currentProgress = 0,
  //   showedProgress = 0,
  //   progressStep = 0,
  //   progressDelta = 0,
  //   startTime = null,
  //   running;

  // (function () {

  //   return requestAnimationFrame
  //     || mozRequestAnimationFrame
  //     || webkitRequestAnimationFrame
  //     || oRequestAnimationFrame
  //     || msRequestAnimationFrame
  //     || function (callback) {
  //       setTimeout(callback, 1000 / 60);
  //     };

  // })();

  // Math.radians = function (degrees) {
  //   return degrees * Math.PI / 180;
  // };


  // progressBar.style.opacity = settings.progressOpacity;
  // barCtx.strokeStyle = settings.progressColor;
  // barCtx.lineWidth = settings.lineWidth;
  // barCtx.lineCap = settings.lineCap;
  // let angleMultiplier = (Math.abs(settings.startDegree) + Math.abs(settings.finalDegree)) / 360;
  // let startAngle = Math.radians(settings.startDegree);
  // document.body.style.overflowY = 'hidden';
  // preloader.style.backgroundColor = settings.preloaderBackground;


  // for (let i = 0; i < imagesAmount; i++) {

  //   let imageClone = new Image();
  //   imageClone.onload = onImageLoad;
  //   imageClone.onerror = onImageLoad;
  //   imageClone.src = images[i].src;

  // }

  // function onImageLoad() {

  //   if (running === true) running = false;

  //   imagesLoaded++;

  //   if (imagesLoaded >= imagesAmount) hidePreloader();

  //   progressStep = showedProgress;
  //   currentProgress = ((100 / imagesAmount) * imagesLoaded) << 0;
  //   progressDelta = currentProgress - showedProgress;

  //   setTimeout(function () {

  //     if (startTime === null) startTime = performance.now();
  //     running = true;
  //     animate();

  //   }, 10);

  // }

  // function animate() {

  //   if (running === false) {
  //     startTime = null;
  //     return;
  //   }

  //   let timeDelta = Math.min(1, (performance.now() - startTime) / settings.preloaderAnimationDuration);
  //   showedProgress = progressStep + (progressDelta * timeDelta);

  //   if (timeDelta <= 1) {


  //     barCtx.clearRect(0, 0, progressBar.width, progressBar.height);
  //     barCtx.beginPath();
  //     barCtx.arc(circleCenterX, circleCenterY, circleRadius, startAngle, (Math.radians(showedProgress * degreesPerPercent) * angleMultiplier) + startAngle);
  //     barCtx.stroke();
  //     requestAnimationFrame(animate);

  //   } else {
  //     startTime = null;
  //   }

  // }

  // function hidePreloader() {
  //   setTimeout(function () {
  //     $("body").addClass("page-loaded");
  //     locoScroll.update();
  //     document.body.style.overflowY = '';
  //   }, settings.preloaderAnimationDuration + 100);
  // }
  // var resizeTimer;


  //LOCOMOTIVE

  const locoScroll = new LocomotiveScroll({
    el: document.querySelector(".smooth-scroll"),
    smooth: true,
    class: 'is-inview',
    getSpeed: true,
    getDirection: true,
    smartphone: {
      smooth: false,
    },
    tablet: {
      smooth: false,
    },
  });

  document.querySelectorAll('a[data-scroll-to]').forEach(link => {
    link.addEventListener('click', function(event) {
      event.preventDefault();
      const target = this.getAttribute('data-scroll-to');
      locoScroll.scrollTo(document.getElementById(target));
    });
  });


  /* BACK TO TOP */
  document.addEventListener('DOMContentLoaded', () => {
    const observedElement = document.querySelector('.slider');
    const scrollButton = document.querySelector('.back-to-top');

    if (locoScroll) {
      scrollButton.addEventListener('click', function() {
        locoScroll.scrollTo(0);
      });
    }

    // Check if the observed element exists and the page height is more than 1500px
    if (observedElement && document.body.scrollHeight > 1500) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) {
            scrollButton.style.opacity = 1;
            scrollButton.style.zIndex = 1000;
          } else {
            scrollButton.style.opacity = 0;
            scrollButton.style.zIndex = -1;
          }
        });
      }, {
        root: null,
        threshold: 0 // Trigger when the element is fully out of view
      });

      observer.observe(observedElement);
    }
  });


})(jQuery);
