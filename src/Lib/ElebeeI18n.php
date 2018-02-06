<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this theme
 * so that it is ready for translation.
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ElebeeI18n.html
 */

namespace ElebeeCore\Lib;


defined( 'ABSPATH' ) || exit;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this theme
 * so that it is ready for translation.
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ElebeeI18n.html
 */
class ElebeeI18n {

    /**
     * The domain specified for this theme.
     *
     * @since 0.1.0
     * @var string The domain identifier for this theme.
     */
    private $domain;

    /**
     * Load the theme text domain for translation.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function loadThemeTEXTDOMAIN() {

        load_theme_textdomain(
            $this->domain,
            dirname( dirname( __DIR__ ) . '/languages/' )
        );

    }

    /**
     * Set the domain equal to that of the specified domain.
     *
     * @since 0.1.0
     *
     * @param string $domain The domain that represents the locale of this theme.
     * @return void
     */
    public function setDomain( string $domain ) {

        $this->domain = $domain;

    }

}
