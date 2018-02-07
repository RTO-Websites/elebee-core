<?php
/**
 * ThemeSupportTitleTag.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib\ControlledTemplate
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ControlledTemplate/ThemeSupportTitleTag.html
 */

namespace ElebeeCore\Lib\ThemeSupport;


defined( 'ABSPATH' ) || exit;

/**
 * Class ThemeSupportTitleTag
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib\ControlledTemplate
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ControlledTemplate/ThemeSupportTitleTag.html
 */
class ThemeSupportTitleTag extends ThemeSupport {

    /**
     * ThemeSupportTitleTag constructor.
     *
     * @since 0.1.0
     *
     * @param string $hook
     */
    public function __construct( string $hook = 'init' ) {

        parent::__construct( $hook );

    }

    /**
     * @since 0.1.0
     */
    public function hookCallback() {

        add_theme_support( 'title-tag' );

    }

}