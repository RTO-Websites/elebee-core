(function ($) {
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/newsticker.default', calcAnimateDuration);
  });

  function calcAnimateDuration($el) {
    var $content = $el.find('.elebee-newsticker-content'),
      width = $content.find('.elebee-newsticker-first').innerWidth() +
        $content.find('.elebee-newsticker-right').length * $content.innerWidth();

    $content.find('.elebee-newsticker-first, .elebee-newsticker-secound')
      .css('animation-duration', width / $content.data('px-per-secound') + 's');
  }
})(jQuery);