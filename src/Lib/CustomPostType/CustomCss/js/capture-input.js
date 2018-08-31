(function ($) {

  let timer = null,
    originalCss = null;

  function init() {

    if(window.opener) {

      window.editor.on('keyup', captureEditorInput);

      $(window).on('unload', function (e) {
        injectCss(originalCss);
      });

      buildCss();

      $.ajax({
        url: customGlobalCss.url,
        cache: false,
        method: 'get',
        success: function (response, textStaus, jqXHR) {
          originalCss = response;
        },
        error: function (jqXHR, textStatus, errorThrown) {
          originalCss = '';
        }
      });

    }

  }

  function captureEditorInput(em, e) {

    // Don't capture the arrow and esc keys
    if( e.keyCode >= 37 && e.keyCode <= 40 || e.keyCode === 27 ) {
      return;
    }

    clearTimeout(timer);
    timer = setTimeout(buildCss, 200);
  }

  function buildCss() {

    // TODO: Add nonce

    $.ajax({
      url: ajaxurl,
      cache: false,
      method: 'post',
      data: {
        action: 'autoUpdate',
        postId: customGlobalCss.postId,
        scss: window.editor.getValue()
      },
      success: buildCssSuccess,
      error: buildCssError
    });

  }

  function buildCssSuccess(response, textStaus, jqXHR) {

    if(response.success) {
      injectCss(response.data);
    }
    else {
      console.log(response.data);
    }

  }

  function buildCssError(jqXHR, textStatus, errorThrown) {

    // TODO: Implement better error handling

    console.log(jqXHR);
    console.log(textStatus);
    console.log(errorThrown);

  }

  function injectCss(css) {

    window.opener.CustomCss.inject(css);

  }

  init();

})(jQuery);