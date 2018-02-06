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


/**
 * Class ThemeCustommizer
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeCustomizer
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeCustomizer/ThemeCustommizer.html
 */
class ThemeCustommizer {

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
     * Add a panel.
     *
     * @since 0.2.0
     *
     * @param Panel $panel
     * @return void
     */
    public function addPanel( Panel $panel ) {

        $this->elementList[$panel->getId()] = $panel;

    }

    /**
     * Add a section.
     *
     * @since 0.2.0
     *
     * @param Section $section
     * @return void
     */
    public function addSection( Section $section ) {

        $this->elementList[$section->getId()] = $section;

    }

    /**
     * Get an element.
     *
     * @since 0.2.0
     *
     * @param string $id
     * @return ThemeCustommizerElement|null
     */
    public function getElement( string $id ): ThemeCustommizerElement {

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