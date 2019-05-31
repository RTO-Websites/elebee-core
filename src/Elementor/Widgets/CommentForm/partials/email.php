<?php

/**
 * @var $fieldWidth string Unit in percent
 * @var $emailLabel string
 * @var $emailPlaceholder string
 * @var $authorEmail string
 * @var $required string Contains HTML
 * @var $cssClass string
 */

$ariaRequired = ( !empty( $required ) ? 'aria-required="true" required="requered"' : '' );
?>

<div class="elementor-column elementor-col-<?php echo $fieldWidth; ?>">
    <label for="comment-author-email"><?php echo $emailLabel . $required; ?></label>
    <input id="comment-author-email" name="comment-author-email" type="email" class="<?php echo $cssClass; ?>"
        <?php echo $ariaRequired; ?> placeholder="<?php echo $emailPlaceholder; ?>" value="<?php echo $authorEmail; ?>" />
</div>