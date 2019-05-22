<?php

/**
 * @var $fieldWidth string Unit in percent
 * @var $emailLabel string
 * @var $emailPlaceholder string
 * @var $authorEmail string
 * @var $required string Contains HTML
 */

$ariaRequired = ( !empty( $requiered ) ? 'aria-required="true"' : '' );
?>

<div class="elementor-field-group elementor-column elementor-col-<?php echo $fieldWidth; ?>">
    <label for="comment-author-email"><?php echo $emailLabel; ?></label><?php echo $required; ?>
    <input id="comment-author-email" name="comment-author-email" type="text" size="30" <?php echo $ariaRequired; ?>
           placeholder="<?php echo $emailPlaceholder; ?>" value="<?php echo $authorEmail; ?>" />
</div>