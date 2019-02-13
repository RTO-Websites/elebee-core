(function ($) {
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/newsticker.default', calcAnimateDuration);
  });

  function calcAnimateDuration($el) {
    var $content = $el.find('.elebee-newsticker-content'),
      $first = $content.find('>span:first-of-type'),
      $secound = $content.find('>span:last-of-type'),
      width = $content.find('>span:first-of-type').innerWidth(),
      elementCount = Math.ceil($content.innerWidth() / width);

    if(!$first.length || !$secound.length) {
      return;
    }

    $first.html($first.html().repeat(elementCount));
    $secound.html($first.html());

    width = $content.find('>span:first-of-type').innerWidth();

    if ($content.find('.elebee-newsticker-right').length) {
      width += $content.innerWidth();

      $first.addClass('elebee-newsticker-animate-200');
      $secound
        .css('animation-delay', width / $content.data('px-per-secound') / 2 + 's')
        .addClass('elebee-newsticker-animate-300');
    } else {
      $first.add($secound).addClass('elebee-newsticker-animate');
    }

    $first.add($secound).css('animation-duration', width / $content.data('px-per-secound') + 's');
  }
})(jQuery);