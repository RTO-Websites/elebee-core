<?php

namespace ElebeeCore\Elementor\Extensions\FormFields;

use Elementor\Controls_Stack;
use Elementor\Widget_Base;
use ElementorPro\Modules\Forms\Module;
use ElementorRto\Pub\ElebeePublic;
use ElebeeCore\Elementor\Extensions\WidgetExtensionBase;

\defined( 'ABSPATH' ) || exit;

class FormFields extends WidgetExtensionBase {

	public function registerFields() {
		$module = new Module();
		$datepicker = new Fields\Datepicker( $this );

		new Fields\Title( $this );
		new Fields\Notice( $this );
		new Fields\Divider( $this );
		$module->add_form_field_type( $datepicker->get_type(), $datepicker );
	}

	public function startControlsSection( Controls_Stack $element ) {

	}

	public function extendRender( string $widgetContent, Widget_Base $widget = null ): string {

	}

	public function addControls( Controls_Stack $element ) {

	}

	public function extendContentTemplate( string $widgetContent, Widget_Base $widget = null ): string {

	}

	public function setConditions( $controlData, $type, $unnecessaryFields ) {
		$terms = [
			'name' => 'field_type',
			'operator' => '!in',
			'value' => [ $type ]
		];

		foreach ( $unnecessaryFields as $field ) {
			if ( !isset( $controlData[ 'fields' ][ $field ][ 'conditions' ]  ) ||
			     !isset( $controlData[ 'fields' ][ $field ][ 'conditions' ][ 'terms' ] ) ) {
				$controlData[ 'fields' ][ $field ][ 'conditions' ][ 'terms' ] = [];
			}
			$controlData[ 'fields' ][ $field ][ 'conditions' ][ 'terms' ][] = $terms;
		}

		return $controlData;
	}
}
