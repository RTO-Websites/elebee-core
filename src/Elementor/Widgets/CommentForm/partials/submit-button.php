<?php

/**
 * @var $buttonClasses string
 * @var $buttonSize string
 * @var $buttonHoverAnimation string
 * @var $buttonIcon string
 * @var $buttonIconAlign string
 * @var $submitText string
 * @var $buttonText string
 */

$icon = '';
if ( !empty( $buttonIcon ) ) {
    $icon = '<span class="elementor-button-icon elementor-align-icon-' . $buttonIconAlign . '">
                <i class="' .$buttonIcon . '" aria-hidden="true"></i>
                <span class="elementor-screen-only">' . $submitText . '</span>
             </span>';
}


$text = '';
if ( !empty( $buttonText ) ) {
    $text = '<span class="elementor-button-text">' . $buttonText . '</span>';
}
?>
<div class="comment-form-messages"></div>
<div class="<?php echo $buttonClasses; ?>">
    <button type="submit" class="elementor-button elementor-size-<?php echo $buttonSize; ?> elementor-animation-<?php echo $buttonHoverAnimation; ?>">
            <span>
                <?php echo $icon; ?>
                <?php echo $text; ?>
            </span>
    </button>
</div>
