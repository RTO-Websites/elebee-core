( function ( $ ) {
    
    function getWidgets ( data ) {
        if ( Array.isArray( data ) ) {
            let founds = [];
            
            for ( let i = 0; i < data.length; i++ ) {
                let result = getWidgets( data[i] );
                
                if ( result ) {
                    founds.push( result );
                }
            }
            
            return founds.flat( 100 );
        }
        
        if ( data.widgetType && data.widgetType === 'comment_form' ) return data;
        if ( data.elements && Array.isArray( data.elements ) ) return getWidgets( data.elements );
        
        return false;
    }
    
    window.getWidgets = getWidgets;
    
    let CommentForm = Backbone.Model.extend( {
        initialize: function () {
            this.set( 'postID', elementor.config.document.id );
            this.addCommentFormListeners();
        },
        
        addCommentFormListeners: function () {
            this.listenToOnce( elementor, 'preview:loaded', this.initPreviewLoaded );
        },
        
        initPreviewLoaded: function () {
            elementor.saver.on( 'after:save', this.saveCategoriesCheck );
        },
        
        saveCategoriesCheck: function ( options ) {
            if ( options.status === 'autosave' ) {
                return;
            }
            
            let commentForms = getWidgets( elementor.config.data );
            
            let data = {
                action: 'comment_form',
                postID: parseInt( elementor.config.document.id ),
                commentForms: []
            };
            
            for ( let i = 0; i < commentForms.length; i++ ) {
                let commentForm = commentForms[i];
    
                let d = {
                    widgetID: commentForm['id'],
                    targetPostID: parseInt( commentForm['settings']['page'] || elementor.config.document.id ),
                };
    
                if ( commentForm['settings']['list_categories'] ) {
                    if ( commentForm['settings']['list_categories'].toJSON ) {
                        d.categories = commentForm['settings']['list_categories'].toJSON();
                    } else {
                        d.categories = commentForm['settings']['list_categories'];
                    }
                } else {
                    d.categories = [];
                }
    
                data.commentForms.push( d );
    
                console.log( commentForm, data );
            }
            
            $.ajax( {
                type: 'post',
                dataType: 'json',
                url: phpInfo.ajaxURL,
                data: data,
                success: this.ajaxSuccess,
                error: this.ajaxError
            } );
        },
        
        ajaxSuccess: function () {
            console.log( "Saved!" );
        },
        
        ajaxError: function () {
            console.log( "Error by saving!" )
        },
        
    } );
    
    $( window ).on( 'elementor:init', function () {
        new CommentForm();
    } );
    
} )( jQuery );