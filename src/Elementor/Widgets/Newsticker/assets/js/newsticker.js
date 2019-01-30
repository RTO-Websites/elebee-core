(function ($) {

  $(window).on('elementor/frontend/init', function () {
    var once = true;
    elementorFrontend.hooks.addAction('frontend/element_ready/newsticker.default', function () {
      if( once ) {
        $('.elebee-newsticker-wrapper').each(function () {
          calcAnimateDuration($(this));
        });

        once = false;
      }
    });
  });

  function calcAnimateDuration($el) {
    var $content = $el.find('.elebee-newsticker-content'),
      repeat = $content.data('repeat'),
      innerWidth = $content.innerWidth(),
      outerWidth = $el.outerWidth(),
      speed = parseInt($content.data('pxps'), 10),
      time = parseInt(innerWidth / speed, 10) || 5;

    if (repeat) {
      if (innerWidth < outerWidth) {
        $content.find('span').css('padding-right', outerWidth - (innerWidth / 2) + 'px');
        innerWidth = $content.innerWidth();
      }

      time = parseInt(innerWidth / speed, 10) || 5;
      repeatAnimation($el, time);
    }

    $content.css('animation-duration', time + 's');
  }

  function repeatAnimation($el, time) {
    setInterval(function () {
      resetAnimation($el);
    }, time / 2 * 1000);
  }

  function resetAnimation($el) {
    $el.find('.elebee-newsticker-container').each(function() {
      $(this).toggleClass('hide');
    });
  }

})(jQuery);
