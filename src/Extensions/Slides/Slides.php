<?php

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Hook widget section to extend widget
 */
add_action( 'elementor/element/slides/section_slides/before_section_end', function ( Widget_Base $widget ) {

	$widget->add_control(
		'use_ratio_hight',
		[
			'label' => __( 'Use Ratio Height', TEXTDOMAIN ),
			'type' => Controls_Manager::SWITCHER,
			'default' => 'No',
			'label_on' => __( 'Yes', TEXTDOMAIN ),
			'label_off' => __( 'No', TEXTDOMAIN ),
			'return_value' => 'elementor-rto-aspect-ratio-slider',
			'prefix_class' => '',
		]);

	$widget->add_responsive_control(
		'aspect-ratio',
		[
			'label' => __( 'Slider Ratio', TEXTDOMAIN ),
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

});