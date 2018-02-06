<?php

/**
 * Panel.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeCustomizer
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeCustomizer/Panel.html
 */

namespace ElebeeCore\Lib\ThemeCustomizer;

/**
 * Class Panel
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeCustomizer
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeCustomizer/Panel.html
 */
class Panel extends ThemeCustommizerElement {

    /**
     * @since 0.2.0
     * @var array
     */
    private $sectionList;

    /**
     * Panel constructor.
     *
     * @since 0.2.0
     *
     * @param string $id
     * @param array  $args
     */
    public function __construct( string $id, array $args ) {

        parent::__construct( $id, $args );
        $this->sectionList = [];

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

        $section->setArg( 'panel', $this->getId() );
        $this->sectionList[$section->getId()] = $section;

    }

    /**
     * Get a section.
     *
     * @since 0.2.0
     *
     * @param string $id
     * @return Section|null
     */
    public function getSection( string $id ): Section {

        return isset( $this->sectionList[$id] ) ? $this->sectionList[$id] : null;

    }

    /**
     * @since 0.2.0
     */
    public function register( \WP_Customize_Manager $wpCustomize ) {

        foreach ( $this->sectionList as $section ) {
            $section->register( $wpCustomize );
        }
        $wpCustomize->add_panel( $this->getId(), $this->getArgs() );

    }

}