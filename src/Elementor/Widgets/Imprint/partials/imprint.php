<?php

/**
 * @var $title string
 * @var $headerSize string
 * @var $headerAttributes string
 */

?>

<<?php echo $headerSize; ?> <?php echo $headerAttributes; ?>>

    <?php echo $title; ?>

</<?php echo $headerSize; ?>>

<div class="text elementor-inline-editing"
        data-elementor-setting-key="text"
        data-elementor-inline-editing-toolbar="advanced">

    <?php echo $text; ?>

</div>