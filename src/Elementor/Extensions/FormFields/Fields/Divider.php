<?php
/**
 * Created by PhpStorm.
 * User: JPlotnikow
 * Date: 12.10.2018
 * Time: 13:59
 */

namespace ElebeeCore\Elementor\Extensions\FormFields\Fields;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use ElementorPro\Plugin;
use ElementorPro\Modules\Forms\Fields as Fields;

defined( 'ABSPATH' ) || exit;

class Divider extends Fields\Field_Base {

	public function get_type() {
		return 'rto-form-divider';
	}

	public function get_name() {
		return 'RTO ' . __( 'Divider', 'elementor' );
	}

	public function update_controls( $widget ) {
		$elementor = Plugin::elementor();

		$control_data = $elementor->controls_manager->get_control_from_stack( $widget->get_unique_name(), 'form_fields' );

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$fieldControls = [
			'rto_form_divider_style' => [
				'name' => 'rto_form_divider_style',
				'label' => __( 'Style', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'elementor' ),
					'double' => __( 'Double', 'elementor' ),
					'dotted' => __( 'Dotted', 'elementor' ),
					'dashed' => __( 'Dashed', 'elementor' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .form-divider' => 'border-top-style: {{VALUE}};',
				],
				'condition' => [
					'field_type' => $this->get_type(),
				]
			],
			'rto_form_divider_height' => [
				'name' => 'rto_form_divider_height',
				'label' => __( 'Height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .form-divider' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'field_type' => $this->get_type(),
				]
			],
			'rto_form_divider_width' => [
				'name' => 'rto_form_divider_width',
				'label' => __( 'Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'px' => [
						'max' => 1000,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .form-divider' => 'display: inline-block; width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'field_type' => $this->get_type(),
				]
			],
			'rto_form_divider_color' => [
				'name' => 'rto_form_divider_color',
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .form-divider' => 'border-top-color: {{VALUE}};',
				],
				'condition' => [
					'field_type' => $this->get_type(),
				]
			],
			'rto_form_divider_align' => [
				'name' => 'rto_form_divider_align',
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .form-divider-container' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'field_type' => $this->get_type(),
				]
			],
			'rto_form_divider_gap' => [
				'name' => 'rto_form_divider_gap',
				'label' => __( 'Gap', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 2,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .form-divider-container' => 'width: 100%; padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'field_type' => $this->get_type(),
				]
			]
		];

		# hide unnecessary fields
		$unnecessaryFields = [
			'field_label',
			'field_value',
			'required'
		];

		$terms = [
			'name' => 'field_type',
			'operator' => '!in',
			'value' => [ $this->get_type() ]
		];

		foreach ( $unnecessaryFields as $field ) {
			if ( !isset( $control_data[ 'fields' ][ $field ][ 'conditions' ]  ) ||
			     !isset( $control_data[ 'fields' ][ $field ][ 'conditions' ][ 'terms' ] ) ) {
				$control_data[ 'fields' ][ $field ][ 'conditions' ][ 'terms' ] = [];
			}
			$control_data[ 'fields' ][ $field ][ 'conditions' ][ 'terms' ][] = $terms;
		}

		$control_data[ 'fields' ] = $this->inject_field_controls( $control_data[ 'fields' ], $fieldControls );
		$widget->update_control( 'form_fields', $control_data );
	}

	public function render( $item, $item_index, $form ) {
		echo '<div class="responsive-aspect-ratio elementor-repeater-item-' . $item[ '_id' ] . '">
				<div class="form-divider-container">
					<div class="form-divider"></div>
				</div>
			</div>';
	}
}