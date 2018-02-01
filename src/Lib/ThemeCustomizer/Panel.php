<?php

/**
 * Panel.php
 */

namespace ElebeeCore\Lib\ThemeCustomizer;

/**
 * Class Panel
 *
 * @package ElebeeCore
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @since 0.2.0
 */
class Panel extends ThemeCustommizerElement {

    /**
     * @var array
     *
     * @ignore
     */
    private $sectionList;

    /**
     * Panel constructor.
     *
     * @param $id
     * @param array $args
     */
    public function __construct( $id, array $args ) {

        parent::__construct( $id, $args );
        $this->sectionList = [];

    }

    /**
     * Add a section.
     *
     * @param Section $section
     *
     * @return void
     */
    public function addSection( Section $section ) {

        $section->setArg( 'panel', $this->getId() );
        $this->sectionList[$section->getId()] = $section;

    }

    /**
     * Get a section.
     *
     * @param $id
     *
     * @return Section|null
     */
    public function getSection( $id ) {

        return isset( $this->sectionList[$id] ) ? $this->sectionList[$id] : null;

    }

    /**
     * {@inheritdoc}
     */
    public function register( \WP_Customize_Manager $wpCustomize ) {

        foreach ( $this->sectionList as $section ) {
            $section->register( $wpCustomize );
        }
        $wpCustomize->add_panel( $this->getId(), $this->getArgs() );

    }

}