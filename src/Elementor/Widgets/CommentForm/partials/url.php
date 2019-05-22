<?php

/**
 * @var $fieldWidth string Unit in percent
 * @var $extraLabel string
 * @var $extraPlaceholder string
 * @var $extraValue string
 * @var $required string Contains HTML
 */

$ariaRequired = ( !empty( $requiered ) ? 'aria-required="true"' : '' );
?>

<div class="elementor-field-group elementor-column elementor-col-<?php echo $fieldWidth; ?>">
    <label for="comment-author-extra"><?php echo $extraLabel; ?></label><?php echo $required; ?>
    <input id="comment-author-extra" name="comment-author-extra" type="text" size="30" <?php echo $ariaRequired; ?>
           placeholder="<?php echo $extraPlaceholder; ?>" value="<?php echo $extraValue; ?>" />
</div>