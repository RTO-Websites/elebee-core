<?php

/**
 * @var $fieldWidth string Unit in percent
 * @var $required string Contains HTML
 * @var $commentLabel string
 * @var $commentPlaceholder string
 */

?>

<div class="elementor-field-group elementor-column elementor-col-<?php echo $fieldWidth; ?>">
    <label for="comment"><?php echo $commentLabel; ?></label><?php echo $required; ?>
    <textarea id="comment" name="comment" placeholder="<?php echo $commentPlaceholder; ?>" aria-required="true"></textarea>
</div>