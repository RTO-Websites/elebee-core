<?php
/**
 * @since 0.2.0
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Lib\ThemeSupport;


class ThemeSupportHTML5 extends ThemeSupport {

    /**
     * ThemeSupportHTML5 constructor.
     * @param string $hook
     */
    public function __construct( string $hook = 'after_setup_theme' ) {

        parent::__construct( $hook );

    }

    /**
     * @since 0.1.0
     */
    public function hookCallback() {

        $args = [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ];
        add_theme_support( 'html5', $args );

    }

}