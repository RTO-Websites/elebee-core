(function ($) {
  'use strict';

  function init() {
    $( '.notice-dismiss' ).on( 'click', noticeDismiss );
  }

  function noticeDismiss() {
    var user_meta_key = $( this ).parent().data( 'name' ),
      data = {
        action: 'dismiss_notice',
        user_id: admin_notice.user_id,
        user_meta_key: user_meta_key,
        nonce: admin_notice.nonce
      };

    $.post( admin_notice.ajaxurl, data);
  }

  window.addEventListener('DOMContentLoaded', init);

})(jQuery);
