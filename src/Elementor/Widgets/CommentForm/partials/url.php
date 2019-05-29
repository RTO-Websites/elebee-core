<?php

/**
 * @var $fieldWidth string Unit in percent
 * @var $extraLabel string
 * @var $extraPlaceholder string
 * @var $extraValue string
 * @var $required string Contains HTML
 * @var $cssClass string
 */

$ariaRequired = ( !empty( $requiered ) ? 'aria-required="true" required' : '' );
?>

<div class="elementor-column elementor-col-<?php echo $fieldWidth; ?>">
    <label for="comment-author-extra"><?php echo $extraLabel . $required; ?></label>
    <input id="comment-author-extra" name="comment-author-extra" type="text" size="30" class="<?php echo $cssClass; ?>"
        <?php echo $ariaRequired; ?> placeholder="<?php echo $extraPlaceholder; ?>" value="<?php echo $extraValue; ?>" />
</div>