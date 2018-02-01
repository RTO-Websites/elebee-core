<?php

/**
 * ThemeCustommizer.php
 */

namespace ElebeeCore\Lib\ThemeCustomizer;


/**
 * Class ThemeCustommizer
 *
 * @package ElebeeCore
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @since 0.2.0
 */
class ThemeCustommizer {

    /**
     * @var array
     *
     * @ignore
     */
    private $elementList;

    /**
     * ThemeCustommizer constructor.
     */
    public function __construct() {

        $this->elementList = [];

    }

    /**
     * Add a panel.
     *
     * @param Panel $panel
     *
     * @return void
     */
    public function addPanel( Panel $panel ) {

        $this->elementList[$panel->getId()] = $panel;

    }

    /**
     * Add a section.
     *
     * @param Section $section
     *
     * @return void
     */
    public function addSection( Section $section ) {

        $this->elementList[$section->getId()] = $section;

    }

    /**
     * Get an element.
     *
     * @param $id
     *
     * @return ThemeCustommizerElement|null
     */
    public function getElement( $id ) {

        return isset( $this->elementList[$id] ) ? $this->elementList[$id] : null;

    }

    /**
     * Add the Wordpress action hook to register the theme customizer elements.
     *
     * @return void
     */
    public function register() {

        add_action( 'customize_register', [ $this, 'actionCustomizeRegister' ] );

    }

    /**
     * Register all theme customizer elements.
     *
     * @param $wpCustomize
     *
     * @return void
     */
    public function actionCustomizeRegister( $wpCustomize ) {

        foreach ( $this->elementList as $element ) {
            $element->register( $wpCustomize );
        }

    }

}