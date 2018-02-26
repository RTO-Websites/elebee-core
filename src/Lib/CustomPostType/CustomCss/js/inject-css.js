(function ($) {

  setTimeout(function () {
    $('.admin-quickbar-postlist[data-post-type="elebee-global-css"] .admin-quickbar-post').each(function () {
      $('<a href="#" class="dashicons dashicons-external"></a>')
        .prependTo($(this).find('.admin-quickbar-post-options'))
        .on('click', openWindow);
    });
  }, 200);

  function openWindow() {
    let url = $(this).next('.dashicons-edit').attr('href');
    window.open(url, '_blank', 'width=700,height=500,left=200,top=100');
  }

  function injectCss(css) {

    let elementId = 'elebee-global-css',
      $elementorPreview = $('#elementor-preview-iframe').contents(),
      $customGlobalCss = $elementorPreview.find('#' + elementId);

    $injection = $('<style id="' + elementId + '">' + css + '</style>');

    if($customGlobalCss.length) {
      $customGlobalCss.replaceWith($injection);
    }
    else {
      $elementorPreview.find('head').append($injection);
    }
  }

  window.CustomCss = {
    inject: injectCss
  };

})(jQuery);