<?php

/**
 * @var $fieldWidth string Unit in percent
 * @var $authorLabel string
 * @var $authorPlaceholder string
 * @var $authorName string
 * @var $required string Contains HTML
 * @var $cssClass string
 */

$ariaRequired = ( !empty( $required ) ? 'aria-required="true" required="required"' : '' );
?>

<div class="elementor-column elementor-col-<?php echo $fieldWidth; ?>">
    <label for="comment-author-name"><?php echo $authorLabel . $required; ?></label>
    <input id="comment-author-name" name="comment-author-name" type="text" class="<?php echo $cssClass; ?>"
        <?php echo $ariaRequired; ?> placeholder="<?php echo $authorPlaceholder; ?>" value="<?php echo $authorName; ?>" />
</div>