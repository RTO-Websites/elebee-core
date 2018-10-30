<?php
/**
 * Created by PhpStorm.
 * User: JPlotnikow
 * Date: 12.10.2018
 * Time: 13:59
 */

namespace ElebeeCore\Elementor\Extensions\FormFields\Fields;

use Elementor\Controls_Manager;
use ElementorPro\Plugin;
use ElementorPro\Modules\Forms\Fields as Fields;

defined( 'ABSPATH' ) || exit;

if ( !defined( '__FORMFIELDS__' ) ) {
	define( '__FORMFIELDS__', get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Elementor/Extensions/FormFields' );
}

class Datepicker extends Fields\Field_Base {
	private $caller = null;

	public function __construct( $caller ) {
		parent::__construct();

		$this->caller = $caller;

		$this->depended_scripts[] = 'jquery-ui-slider';
		$this->depended_scripts[] = 'jquery-ui-timepicker';
		$this->depended_scripts[] = 'elebee-formmods';

		$this->depended_styles[] = 'jquery-ui-slider';
		$this->depended_styles[] = 'jquery-ui-timepicker';

		$this->registerScripts();
	}

	public function get_type() {
		return 'elebee-form-datepicker';
	}

	public function get_name() {
		return __( 'Elebee DatePicker', 'elementor' );
	}

	public function registerScripts() {
		wp_register_script( 'jquery-ui-slider', __FORMFIELDS__. '/lib/jquery-ui/jquery-ui.min.js', [ 'jquery' ] );
		wp_register_script( 'jquery-ui-timepicker', __FORMFIELDS__ . '/lib/jquery-ui-timepicker/jquery-ui-timepicker-addon.js', [ 'jquery-ui-datepicker' ] );
		wp_register_script( 'elebee-formmods', __FORMFIELDS__ . '/js/frontend.js', false, 1, true );

		wp_register_style( 'jquery-ui-slider', __FORMFIELDS__ . '/lib/jquery-ui/jquery-ui.min.css' );
		wp_register_style( 'jquery-ui-timepicker', __FORMFIELDS__ . '/lib/jquery-ui-timepicker/jquery-ui-timepicker-addon.css' );
	}

	public function update_controls( $widget ) {
		$elementor = Plugin::elementor();

		$controlData = $elementor->controls_manager->get_control_from_stack( $widget->get_unique_name(), 'form_fields' );

		if ( is_wp_error( $controlData ) ) {
			return;
		}

		$fieldControls = [
			'elebee_form_datepicker_timeline' => [
				'name' => 'elebee_form_datepicker_timeline',
				'label' => __( 'Visibility of the datepicker', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'past' => [
						'title' => __( 'Past', 'elementor' ),
						'icon' => 'fa fa-hourglass-end',
					],
					'both' => [
						'title' => __( 'Both', 'elementor' ),
						'icon' => 'fa fa-hourglass',
					],
					'future' => [
						'title' => __( 'Future', 'elementor' ),
						'icon' => 'fa fa-hourglass-start',
					],
				],
				'default' => 'both',
				'condition' => [
					'field_type' => $this->get_type(),
				]
			],
			'elebee_form_datepicker_placeholder_past' => [
				'name' => 'elebee_form_datepicker_placeholder_past',
				'label' => __( 'Placeholder from', 'elebee' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'field_type' => $this->get_type(),
				]
			],
			'elebee_form_datepicker_past_align' => [
				'name' => 'elebee_form_datepicker_past_align',
				'label' => __( 'From field align', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => '',
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'selectors' => [
					'{{WRAPPER}} .elebee-datepicker-past-wrapper' => 'float: {{VALUE}};',
				],
			],
			'elebee_form_datepicker_placeholder_future' => [
				'name' => 'elebee_form_datepicker_placeholder_future',
				'label' => __( 'Placeholder till', 'elebee' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'field_type' => $this->get_type(),
				],
			],
			'elebee_form_datepicker_future_align' => [
				'name' => 'elebee_form_datepicker_Future_align',
				'label' => __( 'Till field align', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => '',
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'selectors' => [
					'{{WRAPPER}} .elebee-datepicker-future-wrapper' => 'float: {{VALUE}};',
				],
			],
			'elebee_form_datepicker_fields_width' => [
				'name' => 'elebee_form_datepicker_fields_width',
				'label' => __( 'Fields width', 'elebee' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'px' => [
						'max' => 1000,
					],
				],
				'default' => [
					'size' => 30,
					'unit' => '%',
				],
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'selectors' => [
					'{{WRAPPER}} .elebee-datepicker-wrapper' => 'width: 100%;',
					'{{WRAPPER}} .elebee-datepicker-wrapper > div' => 'display: inline-block; width: {{SIZE}}{{UNIT}};',
				],
			]
		];

		# hide unnecessary fields
		$unnecessaryFields = [
			'field_value'
		];
		$controlData = $this->caller->setConditions( $controlData, $this->get_type(), $unnecessaryFields );

		$controlData[ 'fields' ] = $this->inject_field_controls( $controlData[ 'fields' ], $fieldControls );
		$widget->update_control( 'form_fields', $controlData );
	}

	public function render( $item, $itemIndex, $form ) {
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_register_style( 'jquery-ui', __FORMFIELDS__ . '/lib/jquery-ui/jquery-ui.min.css' );
		wp_enqueue_style( 'jquery-ui' );

		$attributes = [
			'itemIndex'   => $itemIndex,
			'itemSize'    => $item[ 'input_size' ],
		];
		echo '<div class="elebee-datepicker-wrapper">';

		$form->add_render_attribute( $item['_id'], 'type', 'hidden', true );
		$form->add_render_attribute( $item['_id'], 'name', 'form_fields[' . $item['_id'] . ']', true );
		$form->add_render_attribute( $item['_id'], 'class', 'elebee-datepicker-value' );
		echo '<input ' . $form->get_render_attribute_string( $item['_id'] ) . '>';

		$attributes[ 'itemName' ] = 'past';
		$attributes[ 'placeholder' ] = $item[ 'elebee_form_datepicker_placeholder_past' ];
		echo $this::renderField( $form, $attributes );

		$attributes[ 'itemName' ] = 'future';
		$attributes[ 'placeholder' ] = $item[ 'elebee_form_datepicker_placeholder_future' ];
		echo $this::renderField( $form, $attributes );

		echo '</div>';
	}

	private function renderField( $form, $attributes ) {
		$defaults = [
			'itemName'    => 'past',
			'itemIndex'   => 0,
			'itemSize'    => 'sm',
			'size'        => '1',
			'placeholder' => ''
		];
		$attr     = array_merge( $defaults, $attributes );

		$fieldId        = $attr['itemName'] . '-' . $attr['itemIndex'];
		$elementorClass = 'elementor-field-textual elementor-size-' . $attr['itemSize'];

		$form->add_render_attribute( $fieldId, 'type', 'text', true );
		$form->add_render_attribute( $fieldId, 'id', 'form_field-' . $fieldId, true );
		$form->add_render_attribute( $fieldId, 'name', 'form_fields[' . $fieldId . ']', true );
		$form->add_render_attribute( $fieldId, 'class', $elementorClass . ' elebee-datepicker-' . $attr['itemName'] );
		$form->add_render_attribute( $fieldId, 'placeholder', $attr['placeholder'], true );
		$form->add_render_attribute( $fieldId, 'data-time', $attr['itemName'], true );

		return '<div class="elebee-datepicker-' . $attr['itemName'] . '-wrapper">
					<input size="' . $attr['size'] . '" ' . $form->get_render_attribute_string( $fieldId ) . '>
				</div>';
	}
}
