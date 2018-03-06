(function ($) {
  'use strict';

  let adminBarHeight;

  function init() {
    adminBarHeight = $('#wpadminbar').height();
    checkHash();
  }

  function checkHash(e) {
    if (e) {
      e.preventDefault();
    }
    let hash = window.location.hash;
    if (hash !== '' && $(hash).get(0)) {
      setTimeout(function () {
        $('html, body').scrollTop($(hash).offset().top - adminBarHeight);
      }, 0);
    }
  }

  window.addEventListener('DOMContentLoaded', init);
  window.addEventListener('hashchange', checkHash);

})(jQuery);
