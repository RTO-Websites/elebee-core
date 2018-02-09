<?php
/**
 * WidgetPadding.php
 *
 * @since   0.3.0
 *
 * @package ElebeeCore\Extensions\GlobalSettings
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Extensions/GlobalSettings/WidgetPadding.html
 */

namespace ElebeeCore\Extensions\GlobalSettings;


use Elementor\Controls_Manager;
use Elementor\Controls_Stack;

defined( 'ABSPATH' ) || exit;

/**
 * Class WidgetPadding
 *
 * @since   0.3.0
 *
 * @package ElebeeCore\Extensions\GlobalSettings
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Extensions/GlobalSettings/WidgetPadding.html
 */
class WidgetPadding extends GlobalSettingBase {

    /**
     * WidgetPadding constructor.
     *
     * @since 0.3.0
     */
    public function __construct() {

        parent::__construct( 'elementor_padding_between_widgets', 'elementor/element/global-settings/style/before_section_end' );

    }

    /**
     * @since 0.3.0
     */
    public function extend( Controls_Stack $settingPage ) {

        $settingPage->add_control( $this->getSettingName(), [
            'label' => __( 'Widgets Padding', 'elementor' ) . ' (px)',
            'type' => Controls_Manager::NUMBER,
            'min' => 0,
            'placeholder' => '20',
            'description' => __( 'Sets the default space between widgets (Default: 20)', 'elementor' ),
            'selectors' => [
                '.elementor-column-gap-default > .elementor-row > .elementor-column > .elementor-element-populated' => 'padding: {{VALUE}}px',
            ],
        ] );

    }

}