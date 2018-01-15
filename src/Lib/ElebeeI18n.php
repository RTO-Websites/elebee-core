<?php namespace ElebeeCore\Lib;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this theme
 * so that it is ready for translation.
 *
 * @link       https://www.rto.de/
 * @since      0.1.0
 *
 * @package    ElementorRto
 * @subpackage ElementorRto/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this theme
 * so that it is ready for translation.
 *
 * @since      0.1.0
 * @package    ElementorRto
 * @subpackage ElementorRto/includes
 * @author     RTO GmbH <info@rto.de>
 */
class ElebeeI18n {

	/**
	 * The domain specified for this theme.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $domain    The domain identifier for this theme.
	 */
	private $domain;

	/**
	 * Load the theme text domain for translation.
	 *
	 * @since    0.1.0
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
	 * @since    0.1.0
	 * @param    string    $domain    The domain that represents the locale of this theme.
	 */
	public function setDomain( $domain ) {

		$this->domain = $domain;

	}

}
