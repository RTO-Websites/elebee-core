<?php

/**
 * ThemeCustommizer.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeCustomizer
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeCustomizer/ThemeCustommizer.html
 */

namespace ElebeeCore\Lib\ThemeCustomizer;


\defined( 'ABSPATH' ) || exit;

/**
 * Class ThemeCustomizer
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeCustomizer
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeCustomizer/ThemeCustommizer.html
 */
class ThemeCustomizer {

    /**
     * @since 0.2.0
     * @var array
     */
    private $elementList;

    /**
     * ThemeCustommizer constructor.
     *
     * @since 0.2.0
     */
    public function __construct() {

        $this->elementList = [];

    }

    /**
     * Add a fist level element.
     *
     * @since 0.2.0
     *
     * @param Root $element
     * @return void
     */
    public function addElement( Root $element ) {

        $this->elementList[$element->getId()] = $element;

    }

    /**
     * Get an element.
     *
     * @since 0.2.0
     *
     * @param string $id
     * @return Root|null
     */
    public function getElement( string $id ): Root {

        return isset( $this->elementList[$id] ) ? $this->elementList[$id] : null;

    }

    /**
     * Add the Wordpress action hook to register the theme customizer elements.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function register() {

        add_action( 'customize_register', [ $this, 'actionCustomizeRegister' ] );

    }

    /**
     * Register all theme customizer elements.
     *
     * @since 0.2.0
     *
     * @param \WP_Customize_Manager $wpCustomize
     * @return void
     */
    public function actionCustomizeRegister( \WP_Customize_Manager $wpCustomize ) {

        foreach ( $this->elementList as $element ) {
            $element->register( $wpCustomize );
        }

    }

}