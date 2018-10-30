<?php
/**
 * Created by PhpStorm.
 * User: JPlotnikow
 * Date: 18.10.2018
 * Time: 09:09
 */

namespace ElebeeCore\Elementor\Extensions\FormFields\Styles;

use ElebeeCore\Elementor\Extensions\WidgetExtensionBase;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class Notice extends WidgetExtensionBase {

	private $sectionId;

	/**
	 * @since 0.1.0
	 * @var string
	 */
	private $controlPrefix = '';

	/**
	 * Notice constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

		parent::__construct();
		$this->sectionId = 'elebeeSectionFormNotice';
		$this->controlPrefix = 'elebeeFormNotice';
	}

	public function startControlsSection( Controls_Stack $element ) {

		$element->start_controls_section(
			$this->sectionId, [
				'label' => __( 'Notice', 'elebee' ),
				'tab' => $this->getTab(),
			]
		);

	}

	public function addControls( Controls_Stack $element ) {

		$element->add_control(
			$this->controlPrefix . 'Color',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .form-notice' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => $this->controlPrefix . 'Typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .elementor-field-group .form-notice',
			]
		);

		$element->add_control(
			$this->controlPrefix . 'BackgroundColor',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .form-notice' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$element->add_control(
			$this->controlPrefix . 'BorderStyle',
			[
				'label' => __( 'Style', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'elementor' ),
					'double' => __( 'Double', 'elementor' ),
					'dotted' => __( 'Dotted', 'elementor' ),
					'dashed' => __( 'Dashed', 'elementor' ),
					'none' => __( 'None', 'elementor' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .form-notice' => 'border-style: {{VALUE}};',
				],
			]
		);

		$element->add_control(
			$this->controlPrefix . 'BorderColor',
			[
				'label' => __( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .form-notice' => 'border-color: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					$this->controlPrefix . 'BorderStyle!' => 'none',
				],
			]
		);

		$element->add_control(
			$this->controlPrefix . 'BorderWidth',
			[
				'label' => __( 'Border Width', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .form-notice' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					$this->controlPrefix . 'BorderStyle!' => 'none',
				],
			]
		);

		$element->add_control(
			$this->controlPrefix . 'BorderRadius',
			[
				'label' => __( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .form-notice' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					$this->controlPrefix . 'BorderStyle!' => 'none',
				],
			]
		);
	}

	public function extendRender( string $widgetContent, Widget_Base $widget = null ): string {

		return $widgetContent;

	}

	public function extendContentTemplate( string $widgetContent, Widget_Base $widget = null ): string {

		return $widgetContent;

	}


	/**
	 * @since 0.1.0
	 *
	 * @param Element_Base $element
	 * @return void
	 */
	public function setRenderAttributes( Element_Base $element ) {

		if ( $element->get_name() != 'section' ) {
			return;
		}

		if ( $element->get_settings( $this->controlNoticeId ) != true ) {
			return;
		}

		$attributes = [
			'data-elebee-notice' => '',
		];

		if ( $element->get_settings( $this->controlNoticePlaceholderId ) ) {
			$attributes['data-notice-placeholder'] = '';
		}

		if ( !$element->get_settings( $this->controlNoticePositionId ) ) {
			$attributes['data-stick-to-bottom'] = '';
		}

		$offset = $element->get_settings( $this->controlNoticeOffsetId );
		if ( $offset ) {
			$attributes['data-notice-offset'] = $offset['size'] . $offset['unit'];
		}

		$element->set_render_attribute( '_wrapper', $attributes );

	}
}