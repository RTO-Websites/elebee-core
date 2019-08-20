( function ( $ ) {
    
    const cache = {};
    
    function startHoverStar ( e ) {
        let star = $( e.target );
        
        for ( let i = 0; i < star.val(); i++ ) {
            let stars = $( star.parent().parent().children()[i] ).children( 'input' );
            stars.addClass( 'hover' );
        }
    }
    
    function stopHoverStar ( e ) {
        let star = $( e.target );
        
        for ( let i = 0; i < star.val(); i++ ) {
            let stars = $( star.parent().parent().children()[i] ).children( 'input' );
            stars.removeClass( 'hover' );
        }
    }
    
    function showLess ( e ) {
        let target = $( e.target );
        let commentID = e.target.dataset['commentid'];
        
        if ( ! commentID ) {
            return console.error( 'No commentID' );
        }
        
        if ( cache[commentID]['shortContent'] ) {
            return updateContentAndLink( target, cache[commentID]['shortContent'], false );
        }
        
        let content = target.parent().parent().eq( 0 ).children( 'p' ).eq( 0 ).text();
        content = content.substring( 0, 100 );
        updateContentAndLink( target, content, false );
    }
    
    function updateContentAndLink ( elem, content, more ) {
        elem = $( elem );
        
        elem.parent().parent().eq( 0 ).children( 'p' ).eq( 0 ).text( content );
        elem.text( more === true ? commentFormLocalize.language.showLess : commentFormLocalize.language.showMore );
        elem.unbind();
        elem.on( 'click', more === true ? showLess : showMore );
    }
    
    function showMore ( e ) {
        e.preventDefault();
        e.stopPropagation();
        
        let commentID = e.target.dataset['commentid'];
        
        if ( ! commentID ) {
            return console.error( 'No commentID' );
        }
        
        if ( ! cache[commentID] ) cache[commentID] = {};
        if ( ! cache[commentID]['shortContent'] ) cache[commentID]['shortContent'] = $( e.target ).parent().parent().eq( 0 ).children( 'p' ).eq( 0 ).text();
        
        if ( cache[commentID] && cache[commentID]['content'] ) {
            return updateContentAndLink( e.target, cache[commentID]['content'], true );
        }
        
        $.post( commentFormLocalize.ajaxurl, {
            action: 'get_comment_content',
            commentID: commentID
        }, function ( resp ) {
            if ( resp.error ) {
                return alert( 'There was an error, please contact an administrator.' );
            }
            
            if ( resp.content ) {
                if ( ! cache[commentID]['content'] ) cache[commentID]['content'] = resp.content;
                
                updateContentAndLink( e.target, resp.content, true );
            }
        }, 'json' );
        
    }
    
    function clickStar ( e ) {
        console.log( 'TEST' )
        let star = $( e.target );
    
        for ( let i = 0; i < 5; i++ ) {
            let stars = $( star.parent().parent().children()[i] ).children( 'input' );
            console.log( 'Uncheck:', stars );
            stars.removeClass( 'checked' );
        }
        
        for ( let i = 0; i < star.val(); i++ ) {
            let stars = $( star.parent().parent().children()[i] ).children( 'input' );
            stars.addClass( 'checked' );
        }
    }
    
    function formSuccess ( data ) {
        $( '.comment-form-error' ).remove();
        
        try {
            data = JSON.parse( data );
            
            if ( ! data.error ) {
                $( this.form ).html( '<div class="comment-form-success"> <i class="fa fa-check"></i>' + commentFormLocalize.language.commentSuccessSend + ' </div>' );
                return
            }
            
            let error = $( '<div class="comment-form-error"> <i class="fa fa-times-circle"></i> ' + data.message + ' </div>' );
            error.insertBefore( this.form );
            return;
        } catch ( err ) {
            let error = $( '<div class="comment-form-error"> <i class="fa fa-times-circle"></i> ' + data + ' </div>' );
            error.insertBefore( this.form );
        }
    }
    
    function formError () {
        let error = $( '<div class="comment-form-error">  <i class="fa fa-times-circle"></i> Internal Server Error </div>' );
        error.insertBefore( this.form );
    }
    
    function formSubmit ( e ) {
        e.preventDefault();
        e.stopPropagation();
        
        let formData = new FormData( e.target );
        formData.append( 'action', 'post_comment' );
        
        $.ajax( {
            method: 'post',
            url: commentFormLocalize.ajaxurl,
            success: formSuccess.bind( { form: e.target } ),
            error: formError.bind( { form: e.target } ),
            data: new URLSearchParams( formData ),
            processData: false
        } );
    }
    
    function documentReady () {
        let stars = $( '.elebee-rating-star input' );
        let showMores = $( '.showmore' );
        let forms = $( 'form.comment-form' );
        
        stars.on( 'mouseover', startHoverStar );
        stars.on( 'mouseout', stopHoverStar );
        stars.on( 'click', clickStar );
        showMores.on( 'click', showMore );
        forms.on( 'submit', formSubmit )
    }
    
    $( window ).on( 'load', documentReady );
    
} )( jQuery );