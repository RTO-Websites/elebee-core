(function ($) {

  let timer = null;

  window.editor.on('keyup', captureEditorInput);

  function captureEditorInput(em, e) {
    console.log(e);
    console.log(e.keyCode);

    if ((e.keyCode < 65 || e.keyCode > 90) && !(e.shiftKey && (e.keyCode == 50 || e.keyCode == 188)) ) {
      return;
    }

    clearTimeout(timer);
    timer = setTimeout(window.opener.buildCss, 500);
  }

})(jQuery);