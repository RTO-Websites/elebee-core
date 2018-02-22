(function ($) {

  let timer = null;

  window.editor.on('keyup', captureEditorInput);

  function captureEditorInput(em, e) {
    clearTimeout(timer);
    timer = setTimeout(buildCss, 500);
  }

  function buildCss() {

    // TODO: Add nonce

    $.ajax({
      url: ajaxurl,
      cache: false,
      method: 'post',
      data: {
        action: 'autoUpdate',
        postId: postData.id,
        scss: window.editor.getValue()
      },
      success: injectCss
    });

  }

  function injectCss(response, textStaus, jqXHR) {

    window.opener.CustomCss.inject(response.data);

  }

})(jQuery);