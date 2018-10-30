<?php
namespace ElebeeCore\Elementor\Extensions\FormFields\Fields;

use Elementor\Controls_Manager;
use ElementorPro\Plugin;
use ElementorPro\Modules\Forms\Fields;

defined( 'ABSPATH' ) || exit;

class Title extends Fields\Field_Base {

	public function get_type() {
		return 'elebee-form-title';
	}

	public function get_name() {
		return 'Elebee ' . __( 'Title HTML Tag', 'elementor' );
	}

	public function update_controls( $widget ) {
		$elementor = Plugin::elementor();

		$control_data = $elementor->controls_manager->get_control_from_stack( $widget->get_unique_name(), 'form_fields' );

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$field_controls = [
			'elebee_form_title_text' => [
				'name' => 'elebee_form_title_text',
				'label' => __( 'Title', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'field_type' => $this->get_type(),
				]
			],
			'elebee_form_title_tag' =>	[
				'name' => 'elebee_form_title_tag',
				'label' => __( 'Title HTML Tag', 'elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'responsiv' => 'yes',
				'default' => 'h3',
				'condition' => [
					'field_type' => $this->get_type(),
				]
			],
			'elebee_form_title_align' => [
				'name' => 'elebee_form_title_align',
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
				'selectors' => [
					'{{WRAPPER}} .form-title' => 'width: 100%; text-align: {{VALUE}};',
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

		$control_data[ 'fields' ] = $this->inject_field_controls( $control_data[ 'fields' ], $field_controls );
		$widget->update_control( 'form_fields', $control_data );
	}

	public function render( $item, $item_index, $form ) {
		$tag = 'h3';
		$title = '';

		if ( ! empty( $item['elebee_form_title_tag'] ) ) {
			$tag = $item['elebee_form_title_tag'];
		}

		if ( ! empty( $item['elebee_form_title_text'] ) ) {
			$title = $item['elebee_form_title_text'];
		}

		echo '<' . $tag .' class="form-title">' . $title . '</' . $tag .'>';
	}
}
