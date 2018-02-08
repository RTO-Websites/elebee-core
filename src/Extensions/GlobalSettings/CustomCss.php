<?php
/**
 * @since   1.0.0
 * @author  hterhoeven
 * @licence MIT
 */

namespace ElebeeCore\Extensions\GlobalSettings;


use ElebeeCore\Lib\Hooking;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Class CustomCss
 * @package ElebeeCore\Extensions\GlobalSettings
 */
class CustomCss extends Hooking {

    /**
     * @var string
     */
    private $settingName;

    /**
     * CustomCss constructor.
     */
    public function __construct() {

        parent::__construct();

        $this->settingName = 'elebee_custom_global_css';

    }

    /**
     *
     */
    public function defineAdminHooks() {

        $this->getLoader()->addAction( 'elementor/editor/global-settings', $this, 'extend' );
    }

    /**
     *
     */
    public function definePublicHooks() {

    }

    /**
     * @param array $controls
     * @return array
     */
    public function extend( array $controls ) {

        $controls['style']['style']['controls'][$this->settingName] = [
            'label' => __( 'Global CSS', 'elebee' ),
            'type' => Controls_Manager::CODE,
            'description' => sprintf( __( 'Use SCSS syntax (%ssee%s)', 'elementor' ), '<a href="https://sass-lang.com/guide" target="_blank">', '</a>' ),
        ];

        return $controls;

    }

}