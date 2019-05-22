<?php

/**
 * @var $logIn string
 * @var $logInUrl string Url
 */

?>

<div class="elementor-field-group elementor-column">
    <p class="must-log-in">
        <?php echo sprintf( '<a href="%s">' . $logIn . '</a>', $logInUrl ); ?>
    </p>
</div>