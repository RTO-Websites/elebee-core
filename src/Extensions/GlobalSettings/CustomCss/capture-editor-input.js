(function ($) {

  let timer = null;

  if(window.opener) {
    window.editor.on('keyup', captureEditorInput);
  }

  function captureEditorInput(em, e) {

    // Don't capture the arrow keys
    if( e.keyCode >= 37 && e.keyCode <= 40 ) {
      return;
    }

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
      success: injectCss,
      error: error
    });

  }

  function injectCss(response, textStaus, jqXHR) {

    if(response.success) {
      window.opener.CustomCss.inject(response.data);
    }
    else {
      console.log(response.data);
    }

  }

  function error(jqXHR, textStatus, errorThrown) {

    // TODO: Implement better error handling

    console.log(jqXHR);
    console.log(textStatus);
    console.log(errorThrown);

  }

})(jQuery);