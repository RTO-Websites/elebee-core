<?php

use Elementor\Controls_Manager;
use Elementor\Settings;
use ElementorPro\Plugin;
use Elementor\Widget_Base;
use ElementorRto\Pub\ElebeePublic;

defined( 'ABSPATH' ) || exit;

if( ! defined( '__FORMFIELDS__' ) ) {
	define( '__FORMFIELDS__',  get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Extensions/FormFields' );
}

/**
 * Add JS and CSS for Frontend
 */
add_action( 'wp_enqueue_scripts', function(){
	wp_enqueue_script('jquery-ui-slider', __FORMFIELDS__ . '/lib/jquery-ui/jquery-ui.min.js', ['jquery']);
	wp_enqueue_script('jquery-ui-timepicker', __FORMFIELDS__ . '/lib/jquery-ui-timepicker/jquery-ui-timepicker-addon.js', ['jquery-ui-datepicker']);
	wp_enqueue_script( 'rto-formmods', __FORMFIELDS__ . '/js/frontend.js', false, 1 , true );


	wp_register_style( 'jquery-ui-slider',  __FORMFIELDS__ . '/lib/jquery-ui/jquery-ui.min.css' );
	wp_enqueue_style( 'jquery-ui-slider' );
	wp_register_style( 'jquery-ui-timepicker',  __FORMFIELDS__ . '/lib/jquery-ui-timepicker/jquery-ui-timepicker-addon.css' );
	wp_enqueue_style( 'jquery-ui-timepicker' );
} );

/**
 * Add scripts for editor
 */
add_action( 'elementor/editor/before_enqueue_scripts', function(){
	wp_enqueue_script( 'rto-forms-fields', __FORMFIELDS__ . '/js/form.js', false, 1 , true );
}, 99999 );


/**
 * Hook Field renderer to render custom fields
 */
add_action('elementor_pro/forms/render_field/rto-datepicker',function($item, $item_index, Widget_Base $widget){
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_register_style( 'jquery-ui',  __FORMFIELDS__ . '/lib/jquery-ui/jquery-ui.min.css' );
	wp_enqueue_style( 'jquery-ui' );

	echo '<div class="from-till-wrapper elementor-form-fields-wrapper">';
	echo '<div class="elementor-field-group rto-datepicker-from-wrapper">';

	$widget->add_render_attribute( 'input' . $item_index, 'class', 'elementor-field-textual rto-datepicker elementor-column rto-datepicker-from' );
	$widget->add_render_attribute( 'input' . $item_index, 'type', 'text', true );
	$widget->add_render_attribute( 'input' . $item_index, 'placeholder', $item['placeholder'], true );
	$widget->add_render_attribute( 'input' . $item_index, 'data-time', $item['from_till_time'], true );

	echo '<input size="1" ' . $widget->get_render_attribute_string( 'input' . $item_index ) . '>';
	echo '</div>';
	echo '<div class="elementor-field-group rto-datepicker-till-wrapper">';

	$widget->add_render_attribute( 'input' . $item_index, 'class', 'elementor-field-textual  rto-datepicker elementor-column rto-datepicker-till' );
	$widget->add_render_attribute( 'input' . $item_index, 'type', 'text', true );
	$widget->add_render_attribute( 'input' . $item_index, 'placeholder', $item['placeholder2'], true );
	$widget->add_render_attribute( 'input' . $item_index, 'data-time', $item['from_till_time'], true );
	$widget->add_render_attribute( 'input' . $item_index, 'id', 'form_field-field_'. $item_index .'-till', true );
	$widget->add_render_attribute( 'input' . $item_index, 'name', 'form_fields[field_'. $item_index . '-till]', true );

	echo '<input size="1" ' . $widget->get_render_attribute_string( 'input' . $item_index ) . '>';
	echo '</div>';
	echo '</div>';

}, 10, 3);

/**
 * Hook fields to add custom fields
 */
add_filter('elementor_pro/forms/field_types',function($fields) {
	$fields['rto-datepicker']  = 'RTO Von Bis DatePicker';

	return $fields;
});

add_filter('elementor/editor/global-settings' , function($controls) {
	$controls['style']['style']['controls']['elementor_padding_between_widgets'] = [
		'label' => __( 'Widgets Padding', 'elementor' ) . ' (px)',
		'type' => Controls_Manager::NUMBER,
		'min' => 0,
		'placeholder' => '20',
		'description' => __( 'Sets the default space between widgets (Default: 20)', 'elementor' ),
		'selectors' => [
			'.elementor-column-gap-default > .elementor-row > .elementor-column > .elementor-element-populated' => 'padding: {{VALUE}}px',
		],
	];
	
	return $controls;
});

add_action( 'elementor/admin/after_create_settings/' . Settings::PAGE_ID, function(Settings $settings){
	$settings->add_field(\Elementor\Settings::TAB_STYLE, 'style', 'padding_between_widgets', [
			'label' => __( 'Padding Between Widgets', 'elementor' ),
			'field_args' => [
				'type' => 'text',
				'placeholder' => '20',
				'sub_desc' => 'px',
				'class' => 'medium-text',
				'desc' => __( 'Sets the default space between widgets (Default: 20)', 'elementor' ),
			],
		]
	);
}, 50 );
/**
 * Hook widget section to extend control stack
 */
add_action( 'elementor/element/form/section_form_fields/before_section_end', function ( Widget_Base $widget ) {
	$elementor = Plugin::elementor();
	// Check if elementor free is higher than 1.6.0
	if ( method_exists( $widget, 'get_unique_name' ) ) {
		$widget_name = $widget->get_unique_name();
	} else {
		$widget_name = $widget->get_name();
	}

	$fromTillControl =
		[
			'name' => 'from_till_time',
			'label' => __( 'Von Bis Zeitraum', 'elementor-pro' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'options' => [
				'future' => 'Zukunft',
				'both' => 'Beides',
				'past' => 'Vergangenheit'
			],
			'default' => 'future',
		];

	$placeholderControl =
		[
			'name' => 'placeholder2',
			'label' => __( 'Placeholder Bis', TEXTDOMAIN ),
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => '',
			'conditions' => [
				'terms' => [
					[
						'name' => 'field_type',
						'operator' => 'in',
						'value' => [
							'rto-datepicker',

						],
					],
				],
			],

		];

	$control_data = $elementor->controls_manager->get_control_from_stack( $widget_name, 'form_fields' );

	if ( is_wp_error( $control_data ) ) {
		return;
	}
	foreach ( $control_data['fields'] as $index => $field ) {
		if ( 'placeholder' === $field['name']) {
			foreach ( $field['conditions']['terms'] as $termIndex => $termField ) {
				if ( 'field_type' === $termField['name']) {

					array_push($control_data['fields'][ $index ]['conditions']['terms'][$termIndex]['value'], 'rto-datepicker');
				}
			}
		}
	}

	array_push($control_data['fields'], $fromTillControl, $placeholderControl);

	$elementor->controls_manager->update_control_in_stack( $widget, 'form_fields', $control_data );

});

/**
 * Hook widget section to extend control stack
 */
add_action( 'elementor/element/form/section_form_style/before_section_end', function ( Widget_Base $widget ) {
	$elementor = Plugin::elementor();

	// Check if elementor free is higher than 1.6.0
	if ( method_exists( $widget, 'get_unique_name' ) ) {
		$widget_name = $widget->get_unique_name();
	} else {
		$widget_name = $widget->get_name();
	}

	$control_data = $elementor->controls_manager->get_control_from_stack( $widget_name, 'column_gap' );

	$control_data['selectors'] = array_merge($control_data['selectors'], ['{{WRAPPER}} .from-till-wrapper' => 'width: calc( 100% + {{SIZE}}{{UNIT}} );']);

	$elementor->controls_manager->update_control_in_stack( $widget, 'column_gap', $control_data );

});

/**
 * Hook form validation to add custom validation
 */
add_action( 'elementor_pro/forms/validation', function($record,$ajax_handler){
	$fields = $record->get_field( [
		'type' => 'rto-datepicker',
	] );


	foreach ($fields as $field) {
		if ($field['type'] === 'rto-datepicker') {

		}
	}
}, 10, 2 );