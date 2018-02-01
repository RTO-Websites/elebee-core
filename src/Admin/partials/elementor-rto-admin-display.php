<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.rto.de/
 * @since      0.1.0
 *
 * @package    ElementorRto
 * @subpackage ElementorRto/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h1><?php echo get_admin_page_title(); ?></h1>
    <form method="POST" action="options.php">
        <?php settings_fields( 'elementor_rto_settings' ); ?>
        <table class="form-table">
            <?php do_settings_fields( 'elementor_rto_settings', 'default' ); ?>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
