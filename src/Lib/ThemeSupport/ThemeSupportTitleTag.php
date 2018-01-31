<?php
/**
 * @since 0.2.0
 * @author hterhoeven
 * @licence MIT
 */

namespace ElebeeCore\Lib\ThemeSupport;


class ThemeSupportTitleTag extends ThemeSupport {

    /**
     * ThemeSupportTitleTag constructor.
     * @param string $hook
     */
    public function __construct( string $hook = 'init' ) {

        parent::__construct( $hook );

    }

    /**
     * @inheritdoc
     */
    public function hookCallback() {

        add_theme_support( 'title-tag' );

    }

}