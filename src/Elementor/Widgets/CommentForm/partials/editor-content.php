<#
    var sign = settings.comment_required_sign;
    var requiredContainer = typeof sign !== 'undefined' && sign.length > 0 ? '<span class="required">' + sign + '</span>' : '';
    var fieldClass = 'class="elementor-field elementor-field-textual elementor-size-' + settings.input_size + '"';
    var buttonClass = 'class="elementor-button elementor-size-' + settings.input_size + '"';

    var formName = {
        aria: settings.require_name === 'yes' ? 'aria-required="true"' : '',
        require: settings.require_name === 'yes' ? requiredContainer : ''
    };

    var formEmail = {
        aria: settings.require_email === 'yes' ? 'aria-required="true"' : '',
        require: settings.require_email === 'yes' ? requiredContainer : ''
    };

    var formExtra = {
        aria: settings.require_extra === 'yes' ? 'aria-required="true"' : '',
        require: settings.require_extra === 'yes' ? requiredContainer : ''
    };

    var labelsPosition = settings.label_position === 'yes' ?  'above' : 'inline';

    var openCommentsPages = JSON.parse( settings.open_comments_pages );
    if ( typeof( openCommentsPages[ settings.page ] ) === 'undefined' ) { #>
        <?php
        $commentsClosedTitle = '<span class="elebee-notice-title">' . __( 'Comments are disabled!', 'elebee' ) . '</span>';
        $commentsClosedContent = __( 'This form will not be displayed on Frontend.', 'elebee' );
        ?>
        <div class="elebee-notice elebee-notice-warning">
            <# print( '<?php echo $commentsClosedTitle; ?>' ); #>
            <# print( '<?php echo $commentsClosedContent; ?>' ); #>
            <span class="elebee-notice-hint">ID: <# print( settings.page ); #></span>
        </div>
    <# }

    // strip p tag
    settings.comment_gdpr = settings.comment_gdpr.replace( /<p[^>]*>|<\/\p>/g, '' );
#>
<form action="#" method="post" id="commentform" class="elementor-form comment-form elebee-labels-{{{ labelsPosition }}}" novalidate>
    <# if ( settings.show_name === 'yes' ) { #>
    <div class="elementor-column elementor-col-{{{ settings.field_width_name }}}">
        <# if ( '' !== settings.label_name ) { #>
        <label for="comment-author-name">{{{ settings.label_name }}}{{{ formName.require }}}</label>
        <# } #>
        <input id="comment-author-name" name="comment-author-name" type="text" {{{ formName.aria }}}
               placeholder="{{{ settings.placeholder_name }}}" value="" {{{ fieldClass }}} />
    </div>
    <# } #>

    <# if ( settings.show_email === 'yes' ) { #>
    <div class="elementor-column elementor-col-{{{ settings.field_width_email }}}">
        <# if ( '' !== settings.label_email ) { #>
        <label for="comment-author-email">{{{ settings.label_email }}}{{{ formEmail.require }}}</label>
        <# } #>
        <input id="comment-author-email" name="comment-author-email" type="text" {{{ formEmail.aria }}}
               placeholder="{{{ settings.placeholder_email }}}" value="" {{{ fieldClass }}} />
    </div>
    <# } #>

    <# if ( settings.show_extra === 'yes' ) { #>
    <div class="elementor-column elementor-col-{{{ settings.field_width_extra }}}">
        <# if ( '' !== settings.label_extra ) { #>
        <label for="comment-author-extra">{{{ settings.label_extra }}}{{{ formExtra.require }}}</label>
        <# } #>
        <input id="comment-author-extra" name="comment-author-extra" type="text" {{{ formExtra.aria }}}
               placeholder="{{{ settings.placeholder_extra }}}" value="" {{{ fieldClass }}} />
    </div>
    <# } #>
    
    <div class="elementor-field-group elementor-column elementor-col-{{{ settings.field_width_comment }}}">
        <table class="elebee-ratings">
            <input type="hidden" name="widgetID" value="<?php echo $id; ?>">
            
            <# for( let category of settings[ 'list_categories' ] ) { #>
            <tr class="elebee-rating elementor-repeater-item-{{{ category['_id'] }}}">
                <td class="elebee-rating-name">
                    {{{ category[ 'category_label' ] }}}
                    <# let ratingRequired = category['category_required'] ? '<span class="required">' + sign + '</span>'
                    : '' #>
                    <# let ratingRequiredHTML = category['category_required'] ? 'required="required"' : '' #>
                    {{{ ratingRequired }}}
                </td>

                <td class="elebee-rating-stars">
                    <# for ( let i = 1; i <= 5; i++ ) { #>
                    <# let starLabel = i + ' ' + ( i === 1 ? 'Star' : 'Stars' ) #>

                    <div class="elebee-rating-star">
                        <input type="radio"
                               id="elebee-rating-star-{{{ category['_id'] }}}-{{{ i }}}"
                               {{{ ratingRequiredHTML }}}
                               name="elebee-ratings[ {{{ category['_id'] }}} ]"
                               value="{{{ i }}}"
                               title="{{{ starLabel }}}"/>

                        <i class="{{{ category['category_icon'] }}}"></i>

                        <label for="elebee-rating-star-{{{ category['_id'] }}}-{{{ i }}}">{{{ starLabel }}}</label>
                    </div>
                    <# } #>
                </td>
            </tr>
            <# } #>
        </table>
    </div>
    
    <div class="elementor-column elementor-col-{{{ settings.field_width_comment }}}">
        <# if ( '' !== settings.label_comment ) { #>
        <label for="comment">{{{ settings.label_comment }}}{{{ requiredContainer }}}</label>
        <# } #>
        <textarea id="comment" name="comment" aria-required="true" rows="{{{ settings.rows_comment }}}"
                  placeholder="{{{ settings.placeholder_comment }}}" value="" {{{ fieldClass }}}></textarea>
    </div>

    <# if ( settings.show_cookies_opt_in === 'yes' ) { #>
    <div class="elementor-column elebee-checkbox-style">
        <div class="comment-form-cookies-consent elementor-widget-text-editor">
            <input name="wp-comment-cookies-consent" id="wp-comment-cookies-consent" type="checkbox"
                   value="yes">
            <label for="wp-comment-cookies-consent"
                   class="elebee-checkbox-label"><?php _e( 'Save my name, email, and website in this browser for the next time I comment.' ); ?></label>
        </div>
    </div>
    <# } #>

    <# if ( settings.show_gdpr_opt_in === 'yes' ) { #>
    <div class="elementor-column elebee-checkbox-style">
        <div class="elementor-field-option elementor-widget-text-editor">
            <input type="checkbox" value="yes" id="comment-gdpr" name="comment-gdpr" aria-required="true">
            <label for="comment-gdpr" class="elebee-checkbox-label">{{{ settings.comment_gdpr }}}{{{
                requiredContainer }}}</label>
        </div>
    </div>
    <# } #>

    <#
    var buttonClasses = 'elementor-field-group elementor-column elementor-field-type-submit';

    buttonClasses += ' elementor-col-' + ( ( '' !== settings.button_width ) ? settings.button_width : '100' );

    if ( settings.button_width_tablet ) {
    buttonClasses += ' elementor-md-' + settings.button_width_tablet;
    }

    if ( settings.button_width_mobile ) {
    buttonClasses += ' elementor-sm-' + settings.button_width_mobile;
    }

    #>

    <div class="{{ buttonClasses }}">
        <button id="{{ settings.button_css_id }}" type="submit"
                class="elementor-button elementor-size-{{ settings.button_size }} elementor-animation-{{ settings.button_hover_animation }}">
            <span>
                <# if ( settings.button_icon ) { #>
                    <span class="elementor-button-icon elementor-align-icon-{{ settings.button_icon_align }}">
                        <i class="{{ settings.button_icon }}" aria-hidden="true"></i>
                    </span>
                <# } #>

                <# if ( settings.button_text ) { #>
                    <span class="elementor-button-text">{{{ settings.button_text }}}</span>
                <# } #>
            </span>
        </button>
    </div>
</form>

