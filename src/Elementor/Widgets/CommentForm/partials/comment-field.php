<?php

/**
 * @var $fieldWidth string Unit in percent
 * @var $required string Contains HTML
 * @var $commentLabel string
 * @var $commentPlaceholder string
 * @var $cssClass string
 * @var $rows int
 */

?>

<div class="elementor-column elementor-col-<?php echo $fieldWidth; ?>">
    <label for="comment"><?php echo $commentLabel . $required; ?></label>
    <textarea id="comment" name="comment" class="<?php echo $cssClass; ?>" rows="<?php echo $rows; ?>"
              placeholder="<?php echo $commentPlaceholder; ?>" aria-required="true" required></textarea>
</div>