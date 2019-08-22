// Source: https://rudrastyh.com/wordpress/ajax-comments.html
jQuery.extend( jQuery.fn, {
  /*
   * check if field value lenth more than 3 symbols ( for name and comment )
   */
  validate: function() {
    let container =  jQuery( this ).parent();

    if (jQuery( this ).val().length === 0) {
      container.append( '<div class="form-error">' + themeLocalization.fieldIsEmpty + '</div>' );

      return false;
    } else {
      container.find( '.form-error' ).remove();

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
      container.append( '<div class="form-error">' + themeLocalization.fieldIsEmpty + '</div>' );
  }
    else if ( !emailReg.test( emailToValidate ) ) {
      container.append( '<div class="form-error">' + themeLocalization.emailInvalid + '</div>' );
    }
    else {
      container.find( '.form-error' ).remove();

      return true;
    }

    return false;
  },

  validateCheckbox: function() {
    let container =  jQuery( this ).parent();

    if ( !jQuery( this ).is( ':checked' ) ) {
      container.append( '<div class="form-error">' + themeLocalization.required + '</div>' );

      return false;
    } else {
      container.find( '.form-error' ).remove();

      return true;
    }
  },
});

jQuery(function ($) {

  /*
   * On comment form submit
   */
  $( '.comment-form' ).submit( function ( e ) {

    e.preventDefault();

    // define some vars
    let form = $( this ),
      author = form.find( '#comment-author' ),
      email = form.find( '#comment-email' ),
      extra = form.find( '#comment-extra' ),
      cookies = form.find( '#wp-comment-cookies-consent' ),
      gdpr = form.find( '#wp-comment-gdpr-consent' ),
      message = form.find( '.comment-form-messages' ),
      hasErrors = true,
      button = $('button[type="submit"]');

    if ( typeof author !== 'undefined' && author.attr( 'required' ) === 'required' ) {
      author.validate();
    }

    if ( typeof email !== 'undefined' ) {
      if ( email.attr( 'required' ) === 'required' || email.val() && email.val().length > 5 ) {
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
    $( '#comment' ).validate();

    hasErrors = ( form.find( '.form-error' ).length > 0 );
    // if comment form isn't in process, submit it
    if ( !form.hasClass( 'send-form' ) && !hasErrors ) {

      // ajax request
      $.ajax({
        type: 'POST',
        url: themeVars.ajaxUrl, // admin-ajax.php URL
        data: $( this ).serialize() + '&action=ajaxcomments', // send form data + action parameter
        beforeSend: function ( xhr ) {
          // what to do just after the form has been submitted
          form.addClass( 'send-form' );
        },
        error: function (request, status, error) {
          let formError = '';
          if ( status === 500 ) {
            formError = 'Error while adding comment';
          } else if ( status === 'timeout' ) {
            formError = "Error: Server doesn't respond.";
          } else {
            // process WordPress errors
            let wpErrorHtml = request.responseText.split( "<p>" ),
              wpErrorStr = '';

            if ( typeof wpErrorHtml[ 1 ] !== 'undefined' ) {
              wpErrorStr = wpErrorHtml[ 1 ].split( "</p>" );
              formError = wpErrorStr[ 0 ];
            }
            else {
              formError = request.responseText;
            }
          }
          message.html( '<div class="comment-error">' + formError + '</div>' );
        },
        success: function () {
          form[ 0 ].reset();
          message.html( '<div class="comment-error">' + themeLocalization.formSubmitSuccess + '</div>' )
        },
        complete: function () {
          // what to do after a comment has been added
          form.removeClass( 'send-form' );
        }
      });
    }
    return false;
  });
});