// Source: https://rudrastyh.com/wordpress/ajax-comments.html
jQuery.extend( jQuery.fn, {
  /*
   * check if field value lenth more than 3 symbols ( for name and comment )
   */
  validate: function() {
    let container =  jQuery( this ).parent();

    if (jQuery( this ).val().length === 0) {
      container.append( '<div class="error">' + themeLocalization.fieldIsEmpty + '</div>' );

      return false;
    } else {
      container.find( '.error' ).remove();

      return true;
    }
  },
  /*
   * check if email is correct
   * add to your CSS the styles of .error field, for example border-color:red;
   */
  validateEmail: function() {
    let emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/,
      emailToValidate = jQuery( this ).val(),
      container =  jQuery( this ).parent();

    if ( emailToValidate === '' ) {
      container.append( '<div class="error">' + themeLocalization.fieldIsEmpty + '</div>' );
  }
    else if ( !emailReg.test( emailToValidate ) ) {
      container.append( '<div class="error">' + themeLocalization.emailInvalid + '</div>' );
    }
    else {
      container.find( '.error' ).remove();

      return true;
    }

    return false;
  },

  validateCheckbox: function() {
    let container =  jQuery( this ).parent();

    if ( !jQuery( this ).is( ':checked' ) ) {
      container.append( '<div class="error">' + themeLocalization.required + '</div>' );

      return false;
    } else {
      container.find( '.error' ).remove();

      return true;
    }
  },
});

jQuery(function ($) {

  /*
   * On comment form submit
   */
  $('#commentform').submit(function ( e ) {

    e.preventDefault();

    // define some vars
    let author = $( '#comment-author-name' ),
      email = $( '#comment-author-email' ),
      extra = $( '#comment-author-extra' ),
      cookies = $( '#wp-comment-cookies-consent' ),
      gdpr = $( '#comment-gdpr' ),
      button = $('button[type="submit"]');

    if ( typeof author !== 'undefined' && author.attr( 'required' ) === 'required' ) {
      author.validate();
    }

    if ( typeof author !== 'undefined' ) {
      if ( email.attr( 'required' ) === 'required' || email.val().length > 5 ) {
        email.validateEmail();
      }
    }

    if ( typeof extra !== 'undefined' && extra.attr( 'required' ) === 'required' ) {
      extra.validate();
    }

    if ( typeof cookies !== 'undefined' && cookies.attr( 'required' ) === 'required' ) {
      cookies.validateCheckbox();
    }

    if ( typeof gdpr !== 'undefined' && gdpr.attr( 'required' ) === 'required' ) {
      gdpr.validateCheckbox();
    }

    // validate comment in any case
    $('#comment').validate();

    // if comment form isn't in process, submit it
    if (!button.hasClass('loadingform') && !$('#comment-author-name').hasClass('error') && !$('#comment-author-email').hasClass('error') && !$('#comment').hasClass('error')) {

      // ajax request
      $.ajax({
        type: 'POST',
        url: themeVars.ajaxUrl, // admin-ajax.php URL
        data: $(this).serialize() + '&action=ajaxcomments', // send form data + action parameter
        beforeSend: function (xhr) {
          // what to do just after the form has been submitted
          button.addClass('loadingform').val('Loading...');
        },
        error: function (request, status, error) {
          if (status == 500) {
            alert('Error while adding comment');
          } else if (status == 'timeout') {
            alert('Error: Server doesn\'t respond.');
          } else {
            // process WordPress errors
            var wpErrorHtml = request.responseText.split("<p>"),
              wpErrorStr = wpErrorHtml[1].split("</p>");

            // ToDo: display alert on page
            alert(wpErrorStr[0]);
          }
        },
        success: function () {
          // clear textarea field
          $('#comment').val('');

          // ToDo: display success message
        },
        complete: function () {
          // what to do after a comment has been added
          button.removeClass('loadingform').val('Post Comment');
        }
      });
    }
    return false;
  });
});