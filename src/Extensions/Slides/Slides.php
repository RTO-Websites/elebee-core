<?php
/**
 * @since   0.1.0
 *
 * @package ElebeeCore\Extensions\Slides
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Extensions/Slides.html
 */

namespace ElebeeCore\Extensions\Slides;


use ElebeeCore\Extensions\ExtensionBase;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;

defined( 'ABSPATH' ) || exit;

/**
 * Class Slides
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Extensions\Slides
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Extensions/Slides.html
 */
class Slides extends ExtensionBase {

    public function __construct() {

        parent::__construct( 'elementor/element/slides/section_slides/before_section_end' );

    }

    /**
     * @since 0.2.0
     */
    public function extend( Controls_Stack $element ) {

        $element->add_control(
            'use_ratio_hight',
            [
                'label' => __( 'Use Ratio Height', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'No',
                'label_on' => __( 'Yes', 'elebee' ),
                'label_off' => __( 'No', 'elebee' ),
                'return_value' => 'elementor-rto-aspect-ratio-slider',
                'prefix_class' => '',
            ] );

        $element->add_responsive_control(
            'aspect-ratio',
            [
                'label' => __( 'Slider Ratio', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 25.00,
                        'max' => 400.00,
                    ],
                ],
                'tablet_range' => [
                    '%' => [
                        'min' => 25.00,
                        'max' => 400.00,
                    ],
                ],
                'mobile_range' => [
                    '%' => [
                        'min' => 25.00,
                        'max' => 400.00,
                    ],
                ],
                'default' => [
                    'size' => 56.25,
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'size' => 56.25,
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'size' => 56.25,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-rto-aspect-ratio-slider .elementor-slides-wrapper.elementor-slick-slider' => 'padding-top: {{SIZE}}%; position: relative; width: 100%;',
                    '{{WRAPPER}}.elementor-rto-aspect-ratio-slider .elementor-slides' => 'position: absolute; top: 0; bottom: 0; left: 0; right: 0;',
                    '{{WRAPPER}}.elementor-rto-aspect-ratio-slider .elementor-slides .slick-slide,
				{{WRAPPER}}.elementor-rto-aspect-ratio-slider .elementor-slides .slick-list,
				{{WRAPPER}}.elementor-rto-aspect-ratio-slider .elementor-slides .slick-track' => 'height: 100%;',

                ],
                'condition' => [
                    'use_ratio_hight' => 'elementor-rto-aspect-ratio-slider',
                ],
            ]
        );

    }

}
