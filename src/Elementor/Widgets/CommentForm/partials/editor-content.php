<#
    var sign = settings.comment_required_sign;
    var requiredContainer = typeof sign !== 'undefined' && sign.length > 0 ? '<span class="required">' + sign + '</span>' : '';

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

    // strip p tag
    settings.comment_gdpr = settings.comment_gdpr.replace( /<p[^>]*>|<\/\p>/g, '' );
#>
<form action="#" method="post" id="commentform" class="comment-form" novalidate>
    <# if ( settings.show_name === 'yes' ) { #>
    <div class="elementor-field-group elementor-column elementor-col-{{{ settings.field_width_name }}}">
        <label for="comment-author-name">{{{ settings.label_name }}}</label>{{{ formName.require }}}
        <input id="comment-author-name" name="comment-author-name" type="text" size="30" {{{ formName.aria }}}
               placeholder="{{{ settings.placeholder_name }}}" value="" />
    </div>
    <# } #>

    <# if ( settings.show_email === 'yes' ) { #>
    <div class="elementor-field-group elementor-column elementor-col-{{{ settings.field_width_email }}}">
        <label for="comment-author-email">{{{ settings.label_email }}}</label>{{{ formEmail.require }}}
        <input id="comment-author-email" name="comment-author-email" type="text" size="30" {{{ formEmail.aria }}}
               placeholder="{{{ settings.placeholder_email }}}" value="" />
    </div>
    <# } #>

    <# if ( settings.show_extra === 'yes' ) { #>
    <div class="elementor-field-group elementor-column elementor-col-{{{ settings.field_width_extra }}}">
        <label for="comment-author-extra">{{{ settings.label_extra }}}</label>{{{ formExtra.require }}}
        <input id="comment-author-extra" name="comment-author-extra" type="text" size="30" {{{ formExtra.aria }}}
               placeholder="{{{ settings.placeholder_extra }}}" value="" />
    </div>
    <# } #>

    <div class="elementor-field-group elementor-column elementor-col-{{{ settings.field_width_comment }}}">
        <label for="comment">{{{ settings.label_comment }}}</label><# print( requiredContainer ); #>
        <textarea id="comment" name="comment" placeholder="{{{ settings.placeholder_comment }}}" aria-required="true"></textarea>
    </div>

    <# if ( settings.show_cookies_opt_in === 'yes' ) { #>
    <div class="elementor-field-group elementor-column">
        <p class="comment-form-cookies-consent">
            <input name="wp-comment-cookies-consent" id="wp-comment-cookies-consent" type="checkbox" value="yes">
            <label for="wp-comment-cookies-consent"><?php _e( 'Save my name, email, and website in this browser for the next time I comment.' ) ; ?></label>
        </p>
    </div>
    <# } #>

    <# if ( settings.show_gdpr_opt_in === 'yes' ) { #>
    <div class="elementor-field-group elementor-column">
        <p class="elementor-field-option">
            <input type="checkbox" value="yes" id="comment-gdpr" name="comment-gdpr" aria-required="true">
            <label for="comment-gdpr">{{{ settings.comment_gdpr }}}</label><# print( requiredContainer ); #>
        </p>
    </div>
    <# } #>

    <p class="form-submit">
        <input name="submit" type="submit" id="submit" class="submit elementor-animation-" value="{{{ settings.label_button }}}" />
    </p>
</form>

