(function ($) {

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/newsticker.default', function () {
      $('.rto-newsticker-wrapper').each(function () {
        calcAnimateDuration($(this));
      });
    });
  });

  function calcAnimateDuration(el) {
    let $content = el.find('.rto-newsticker-content'),
      innerWidth = $content.innerWidth(),
      speed = parseInt($content.data('pxps'), 10),
      time = parseInt(innerWidth / speed, 10) || 5;

    time = repeatAnimation($content, time);

    $content.css('animation-duration', `${time}s`);
  }

  function repeatAnimation($content, time) {
    if ($content.data('repeat')) {
      setInterval(function () {
        resetAnimation($content);
      }, time / 2 * 1000);
    }

    return time;
  }

  function resetAnimation($ele) {
    $ele.closest('.rto-newsticker-container').toggleClass('hide');
  }

})(jQuery);
