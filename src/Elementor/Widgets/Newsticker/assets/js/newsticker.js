(function ($) {

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/newsticker.default', function ($newsticker) {
      calcAnimateDuration($newsticker.find('.elebee-newsticker-wrapper'));
    });
  });

  function calcAnimateDuration($el) {
    var $content = $el.find('.elebee-newsticker-content'),
      startPosition = $content.data('start-position'),
      innerWidth = $content.innerWidth(),
      speed = parseInt($content.data('pxps'), 10),
      time = parseInt(innerWidth / speed, 10) || 5;

    setAnimationDuration($content, time, startPosition);
  }

  function setAnimationDuration($content, time, startPosition) {
    if( startPosition === 'right' ) {
      $content.find('.elebee-newsticker-secound').css('animation-delay', time / 2 + 's');
      $content.find('.elebee-newsticker-first, .elebee-newsticker-secound').css('animation-duration', time + 's');
    } else {
      $content.find('.elebee-newsticker-first, .elebee-newsticker-secound').css('animation-duration', time / 2 + 's');
    }
  }

})(jQuery);