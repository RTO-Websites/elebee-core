<?php

/**
 * MetaKeyColorPicker.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKeyColorPicker.html
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Template;

defined( 'ABSPATH' ) || exit;

/**
 * Class MetaKeyColorPicker
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\MetaBox
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/MetaBox/MetaKeyColorPicker.html
 */
class MetaKeyColorPicker extends MetaKeyBase {

    /**
     * MetaKeyTextfield constructor.
     *
     * @since   0.2.0
     *
     * @param string $key
     * @param string $label
     * @param int    $type
     */
    public function __construct( string $key, string $label, int $type = self::TYPE_DEFAULT ) {

        $defaultTemplate = new Template( __DIR__ . '/partials/meta-key-color-picker-default.php' );
        $this->setTemplate( $defaultTemplate );

        add_action( 'admin_enqueue_scripts', [ $this, 'actionAdminEnqueueScripts' ] );

        parent::__construct( $key, $label, $type );

    }

    /**
     * Admin enqueue scripts action callback
     *
     * @since   0.2.0
     *
     * @return void
     */
    public function actionAdminEnqueueScripts() {

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );

    }

}