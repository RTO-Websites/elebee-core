<?php

/**
 * @var $label string Contains HTML
 * @var $required string Contains HTML
 * @var string $type [ gdpr, cookies ]
 */

$ariaRequired = ( !empty( $required ) ? 'aria-required="true" required="required"' : '' );

?>

<div class="elementor-column elebee-checkbox-style">
    <div class="comment-form-<?php echo $type; ?>-consent elementor-widget-text-editor">
        <input name="wp-comment-<?php echo $type; ?>-consent" id="wp-comment-<?php echo $type; ?>-consent"
               type="checkbox" value="yes" <?php echo $ariaRequired; ?>>
        <label for="wp-comment-<?php echo $type; ?>-consent" class="elebee-checkbox-label"><?php echo $label . $required; ?></label>
    </div>
</div>