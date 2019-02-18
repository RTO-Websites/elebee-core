(function ($) {
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/newsticker.default', calcAnimateDuration);
  });

  function calcAnimateDuration($el) {
    var $content = $el.find('.elebee-newsticker-content'),
      $first = $content.find('>span:first-of-type'),
      $second = $content.find('>span:last-of-type'),
      width = $first.innerWidth(),
      elementCount = Math.ceil($content.innerWidth() / width);

    if(!$first.length || !$second.length) {
      return;
    }

    $first.html($first.html().repeat(elementCount));
    $second.html($first.html());

    width = $first.innerWidth();

    if ($content.find('.elebee-newsticker-right').length) {
      width += $content.innerWidth();

      $first.addClass('elebee-newsticker-animate-200');
      $second
        .css('animation-delay', width / $content.data('px-per-second') / 2 + 's')
        .addClass('elebee-newsticker-animate-300');
    } else {
      $first.add($second).addClass('elebee-newsticker-animate');
    }

    $first.add($second).css('animation-duration', width / $content.data('px-per-second') + 's');
  }
})(jQuery);