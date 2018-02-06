<?php
/**
 * ThemeSupportHTML5.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib\ControlledTemplate
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ControlledTemplate/ThemeSupportHTML5.html
 */

namespace ElebeeCore\Lib\ThemeSupport;


/**
 * Class ThemeSupportHTML5
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib\ControlledTemplate
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ControlledTemplate/ThemeSupportHTML5.html
 */
class ThemeSupportHTML5 extends ThemeSupport {

    /**
     * ThemeSupportHTML5 constructor.
     *
     * @since 0.1.0
     *
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