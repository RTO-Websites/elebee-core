(function ($) {

  window.buildCss = buildCss;

  setTimeout(function () {
    $('.admin-quickbar-postlist[data-post-type="elebee-global-css"] .admin-quickbar-post').each(function () {
      $('<a href="#" class="dashicons dashicons-external"></a>')
        .prependTo($(this).find('.admin-quickbar-post-options'))
        .on('click', openWindow);
    });
  }, 300);

  function openWindow() {
    let url = $(this).next('.dashicons-edit').attr('href');
    window.open(url, '_blank', 'width=700,height=500,left=200,top=100');
  }

  function buildCss() {
    console.log('building CSS...');
  }

})(jQuery);