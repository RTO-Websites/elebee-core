<?php

/**
 * @var $title string
 * @var $titleTag string
 * @var $headerAttributes string
 * @var $text string
 */

?>

<<?php echo $titleTag; ?> <?php echo $headerAttributes; ?>>

    <?php echo $title; ?>

</<?php echo $titleTag; ?>>

<div class="text elementor-inline-editing"
        data-elementor-setting-key="text"
        data-elementor-inline-editing-toolbar="advanced">

    <?php echo $text; ?>

</div>