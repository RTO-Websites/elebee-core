<?php

/**
 * @var string $type [ author, email, url ]
 * @var string $width Unit in percent
 * @var string $label
 * @var string $placeholder
 * @var string $value
 * @var string $required Contains HTML
 * @var string $cssClass
 */

$ariaRequired = ( !empty( $required ) ? 'aria-required="true" required="required"' : '' );
$labelHtml = '';
if ( !empty( $label ) ) {
    $labelHtml = '<label for="comment-' . $type . '">' . $label . $required . '</label>';
}
?>

<div class="elementor-column elementor-col-<?php echo $width; ?>">
    <?php echo $labelHtml; ?>
    <input id="comment-<?php echo $type; ?>" name="comment-<?php echo $type; ?>" type="text" class="<?php echo $cssClass; ?>"
        <?php echo $ariaRequired; ?> placeholder="<?php echo $placeholder; ?>" value="<?php echo $value; ?>" />
</div>