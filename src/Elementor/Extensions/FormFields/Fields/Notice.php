<?php
namespace ElebeeCore\Elementor\Extensions\FormFields\Fields;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use ElementorPro\Plugin;
use ElementorPro\Modules\Forms\Fields as Fields;

defined( 'ABSPATH' ) || exit;

class Notice extends Fields\Field_Base {

	public function get_type() {
		return 'rto-form-notice';
	}

	public function get_name() {
		return 'RTO ' . __( 'Notice', 'elementor' );
	}

	public function update_controls( $widget ) {
		$elementor = Plugin::elementor();

		$control_data = $elementor->controls_manager->get_control_from_stack( $widget->get_unique_name(), 'form_fields' );

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$field_controls = [
			'rto_form_notice_text' => [
				'name' => 'rto_form_notice_text',
				'label' => __( 'Notice', 'elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'condition' => [
					'field_type' => $this->get_type(),
				]
			],
			'rto_form_notice_align' => [
				'name' => 'rto_form_title_align',
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
					'{{WRAPPER}} .form-notice' => 'width: 100%; text-align: {{VALUE}};',
				],
				'condition' => [
					'field_type' => $this->get_type(),
				]
			]
		];


		# hide unnecessary fields
		$unnecessaryFields = [
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
		$text = '';

		if ( ! empty( $item['rto_form_notice_text'] ) ) {
			$text = $item['rto_form_notice_text'];
		}

		echo '<p class="form-notice">' . $text . '</p>';
	}
}
