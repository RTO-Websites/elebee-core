(function ($) {
  'use strict';

  let adminBarHeight;
  let selector = 'elebee_google_analytics_tracking_id_control';
  let gaInputSelector = '#_customize-input-'+ selector;
  let localization = {
    noticeGoogleTrackingIdInvalid: 'Goolge Tracking ID format is invalid.'
  };

  function init() {
    $( document ).on( 'blur', gaInputSelector, checkGaIdFormat );

    adminBarHeight = $('#wpadminbar').height();
    checkHash();
    initLocalization();
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

  function checkGaIdFormat() {
    let gaId = $( gaInputSelector ).val();
    let noticeContainer = $( '#customize-control-' + selector + ' .customize-control-notifications-container' );
    let noticeList = $( '#customize-control-' + selector + ' .customize-control-notifications-container ul' );

    if( gaId !== '' && !/(UA|YT|MO)-\d+-\d+/i.test( gaId ) ) {
      noticeContainer.show();
      noticeList.html(
        '<li class="notice notice-warning">' + localization.noticeGoogleTrackingIdInvalid + '</div>'
      );
    }
    else {
      noticeContainer.hide();
    }
  }

  function initLocalization() {
    if ( typeof l10n === 'object' ) {
      for ( let property in l10n ) {
          localization[ property ] = l10n[ property ];
      }
    }
  }

  window.addEventListener('DOMContentLoaded', init);
  window.addEventListener('hashchange', checkHash);

})(jQuery);
