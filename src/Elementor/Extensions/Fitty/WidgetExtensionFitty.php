<?php
/**
 * FittyJs.php
 *
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Elementor\Extensions\Fitty;


use ElebeeCore\Elementor\Extensions\WidgetExtensionBase;
use ElebeeCore\Lib\Elebee;
use ElebeeCore\Lib\Util\Template;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Widget_Base;

class WidgetExtensionFitty extends WidgetExtensionBase {

    private static $scriptEnqueued = false;

    private $sectionId;

    private $controlToggleId;

    public function __construct() {

        $this->sectionId = 'elebeeFitty';
        $this->controlToggleId = 'elebeeFittyToggle';

        parent::__construct();
        $this->defineEditorHooks();

    }

    private function definePublicHooks() {

        $this->getLoader()->addAction( 'wp_enqueue_scripts', $this, 'enqueueScripts' );

    }

    private function defineEditorHooks() {

        $this->getLoader()->addAction( 'wp_enqueue_scripts', $this, 'enqueueScripts' );

    }

    public function enqueueScripts() {

        wp_enqueue_script( $this->sectionId, get_template_directory_uri() . '/vendor/rto-websites/elebee-core/src/Elementor/Extensions/Fitty/node_modules/fitty/dist/fitty.min.js', Elebee::VERSION, true );

    }

    public function startControlsSection( Controls_Stack $element ) {

    }

    public function addControls( Controls_Stack $element ) {

        $element->add_control( $this->controlToggleId, [
            'label' => __( 'Fitty', 'elebee' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __( 'On', 'elementor' ),
            'label_off' => __( 'Off', 'elementor' ),
            'return_value' => 'true',
        ] );

    }

    public function extendRender( string $widgetContent, Widget_Base $widget = null ): string {

        if ( $widget === null || !$this->isRegisteredTo( $widget->get_name() ) ) {
            return $widgetContent;
        }

        if ( $widget->get_settings( $this->controlToggleId ) != true ) {
            return $widgetContent;
        }

        if( ! self::$scriptEnqueued ) {
            $this->definePublicHooks();
            self::$scriptEnqueued = true;
        }

        $template = new Template( __DIR__ . '/partials/script.php', [
            'content' => $widgetContent,
            'elementId' => $widget->get_id(),
        ] );

        return $template->getRendered();

    }

    public function extendContentTemplate( string $widgetContent, Widget_Base $widget = null ): string {

        return $widgetContent;

    }


}