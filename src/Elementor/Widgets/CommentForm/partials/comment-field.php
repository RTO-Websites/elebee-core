<?php

/**
 * @var $fieldWidth string Unit in percent
 * @var $required string Contains HTML
 * @var $commentLabel string
 * @var $commentPlaceholder string
 * @var $cssClass string
 * @var $rows int
 */

$labelHtml = '';
if ( !empty( $label ) ) {
    $labelHtml = '<label for="comment">' . $commentLabel . $required . '</label>';
}

?>

<div class="elementor-column elementor-col-<?php echo $fieldWidth; ?>">
    <?php echo $labelHtml; ?>
    <textarea id="comment" name="comment" class="<?php echo $cssClass; ?> elebee-comment-field" rows="<?php echo $rows; ?>"
            placeholder="<?php echo $commentPlaceholder; ?>" aria-required="true" required="required"></textarea>
</div>