(function ($) {
  $(document).ready(function () {
    var allPanels = $('.accordion > dd').hide();
    $('.accordion > dt > a').click(function () {
      var panel = $(this).parent().next();
      panel.slideToggle();
      setTimeout(function() {
        locoScroll.update();
      }, 400);
      return false;
    });
  });
})(jQuery);