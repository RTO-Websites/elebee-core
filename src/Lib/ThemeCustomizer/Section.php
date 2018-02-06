<?php

/**
 * Section.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeCustomizer
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeCustomizer/Section.html
 */

namespace ElebeeCore\Lib\ThemeCustomizer;


/**
 * Class Section
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\ThemeCustomizer
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeCustomizer/Section.html
 */
class Section extends ThemeCustommizerElement {

    /**
     * @since 0.2.0
     * @var array
     */
    private $settingList;

    /**
     * Section constructor.
     *
     * @since 0.2.0
     *
     * @param string $id
     * @param array  $args
     */
    public function __construct( string $id, array $args ) {

        parent::__construct( $id, $args );
        $this->settingList = [];

    }

    /**
     * Add a setting.
     *
     * @since 0.2.0
     *
     * @param Setting $setting
     * @return void
     */
    public function addSetting( Setting $setting ) {

        $setting->getControl()->setArg( 'section', $this->getId() );
        $this->settingList[$setting->getId()] = $setting;

    }

    /**
     * @since 0.2.0
     */
    public function register( \WP_Customize_Manager $wpCustomize ) {

        $wpCustomize->add_section( $this->getId(), $this->getArgs() );

        foreach ( $this->settingList as $setting ) {
            $setting->register( $wpCustomize );
        }

    }

}