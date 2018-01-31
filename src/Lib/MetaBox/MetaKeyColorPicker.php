<?php

/**
 * MetaKeyColorPicker.php
 */

namespace ElebeeCore\Lib\MetaBox;


use ElebeeCore\Lib\Template;

/**
 * Class MetaKeyColorPicker
 *
 * @package ElebeeCore
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @since 0.2.0
 */
class MetaKeyColorPicker extends MetaKey {

    /**
     * MetaKeyTextfield constructor.
     *
     * @param $key
     * @param $label
     * @param int $type
     */
    public function __construct( $key, $label, $type = self::TYPE_DEFAULT ) {

        $defaultTemplate = new Template( __DIR__ . '/partials/meta-key-color-picker-default.php' );
        $this->setTemplate( $defaultTemplate );

        add_action( 'admin_enqueue_scripts', [ $this, 'actionAdminEnqueueScripts' ] );

        parent::__construct( $key, $label, $type );

    }

    /**
     * Admin enqueue scripts action callback
     *
     * @return void
     */
    public function actionAdminEnqueueScripts() {

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );

    }

}