(function ($) {
  $(document).ready(function () {
    "use strict";
    /* BACK BUTTON RELOAD */
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
    })
    /* SEARCH */
    $('.search-button').on('click', function () {
      $(".search-box").toggleClass("active")
      $(".section-wrapper").toggleClass("no-transform")
    })
    /* AJAX ERROR MSG */
    $(window).on('ajaxInvalidField', function(event, fieldElement, fieldName, errorMsg, isFirst) {
      $(fieldElement).closest('.inner').addClass('has-error');
    });
    $(document).on('ajaxPromise', '[data-request]', function() {
      $(this).closest('form').find('.inner.has-error').removeClass('has-error');
    });
    //TAB
    // $(".tab-nav li").on('click', function (e) {
    //   $(".tab-item").hide();
    //   $(".tab-nav li").removeClass('active');
    //   $(this).addClass("active");
    //   var selected_tab = $(this).find("a").attr("href");
    //   $(selected_tab).stop().show();
    //   return false;
    // });
  });
  // END DOCUMENT READY

  // DATA BACKGROUND IMAGE
  var pageSection = $("*");
  pageSection.each(function (indx) {
    if ($(this).attr("data-background")) {
      $(this).css("background", "url(" + $(this).data("background") + ")")
    }
    if ($(this).attr("data-background-fixed") == '1') {
      $(this).css("background-", "url(" + $(this).data("background") + ")")
    }
  });

  // DATA BACKGROUND COLOR
  var pageSection = $("*");
  pageSection.each(function (indx) {
    if ($(this).attr("data-background")) {
      $(this).css("background", $(this).data("background"))
    }
  });  

  /* BACK TO TOP */
  document.addEventListener('DOMContentLoaded', () => {
    const observedElement = document.querySelector('.slider') || document.querySelector('.page-header')
    const scrollButton = document.querySelector('.back-to-top')

    scrollButton.addEventListener('click', function() {
      if (typeof locoScroll !== 'undefined') {
        locoScroll.scrollTo(0)
      } else {
        window.scrollTo(0, 0)
      }
    })

    // Check if the observed element exists and the page height is more than 1500px
    if (observedElement && document.body.scrollHeight > 1500) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) {
            scrollButton.style.opacity = 1
            scrollButton.style.zIndex = 1000
          } else {
            scrollButton.style.opacity = 0
            scrollButton.style.zIndex = -1
          }
        });
      }, {
        root: null,
        threshold: 0 // Trigger when the element is fully out of view
      })

      observer.observe(observedElement)
    }
  });
})(jQuery);
