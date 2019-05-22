<?php

/**
 * @var $fieldWidth string Unit in percent
 * @var $authorLabel string
 * @var $authorPlaceholder string
 * @var $authorName string
 * @var $required string Contains HTML
 */

$ariaRequired = ( !empty( $requiered ) ? 'aria-required="true"' : '' );
?>

<div class="elementor-field-group elementor-column elementor-col-<?php echo $fieldWidth; ?>">
    <label for="comment-author-name"><?php echo $authorLabel; ?></label><?php echo $required; ?>
    <input id="comment-author-name" name="comment-author-name" type="text" size="30" <?php echo $ariaRequired; ?>
           placeholder="<?php echo $authorPlaceholder; ?>" value="<?php echo $authorName; ?>" />
</div>