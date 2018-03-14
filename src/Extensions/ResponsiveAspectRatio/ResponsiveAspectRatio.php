<?php
/**
 * @since   1.0.0
 * @author  hterhoeven
 * @licence MIT
 */

namespace ElebeeCore\Extensions\ResponsiveAspectRatio;


use ElebeeCore\Extensions\ExtensionBase;
use ElebeeCore\Lib\Template;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Settings;
use Elementor\Settings_Controls;
use Elementor\Widget_Base;

/**
 * Class ResponsiveAspectRatio
 * @package ElebeeCore\Extensions
 */
class ResponsiveAspectRatio extends ExtensionBase {

    private $sectionId;

    private $controlToggleId;

    private $controlAspectRatioId;

    private $controlCustomAspectRatioId;

    public function __construct( string $tab ) {

        parent::__construct( $tab );
        $this->sectionId = 'sectionResponsiveAspectRatio';
        $this->controlToggleId = 'aspectRatioToggle';
        $this->controlAspectRatioId = 'aspectRatio';
        $this->controlCustomAspectRatioId = 'customAspectRatio';
        $this->definePublicHooks();

    }

    private function definePublicHooks() {

        $this->getLoader()->addFilter( 'elementor/widget/render_content', $this, 'extendRendering', 10, 2 );
        $this->getLoader()->addFilter( 'elementor/widget/print_template', $this, 'extendContentTemplate', 10, 2 );

    }

    public function extendControlStack( Controls_Stack $element ) {

        $element->start_controls_section(
            $this->sectionId, [
                'label' => __( 'Responsive Aspect Ratio', 'elebee' ),
                'tab' => $this->getTab(),
            ]
        );

        $element->add_control(
            $this->controlToggleId, [
                'label' => __( 'Toggle Responsive Aspect Ratio', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Fix', 'elementor' ),
                'label_off' => __( 'Default', 'elementor' ),
                'return_value' => 'true',
            ]
        );

        $element->add_control(
            $this->controlAspectRatioId,
            [
                'label' => __( 'Ratio', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'square',
                'options' => [
                    'square' => __( 'Square', 'elebee' ),
                    '16by9' => __( '16:9', 'elebee' ),
                    '4by3' => __( '4:3', 'elebee' ),
                    'custom' => __( 'Custom', 'elebee' ),
                ],
                'condition' => [
                    $this->controlToggleId => 'true',
                ],
            ]
        );

        $element->add_control(
            $this->controlCustomAspectRatioId,
            [
                'label' => __( 'Custom Ratio', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 25.00,
                        'max' => 400.00,
                    ],
                ],
                'default' => [
                    'size' => 56.25,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .responsive-aspect-ratio::after' => 'padding-top: {{SIZE}}%;',
                ],
                'condition' => [
                    $this->controlToggleId => 'true',
                    $this->controlAspectRatioId => 'custom',
                ],
            ]
        );

        $element->end_controls_section();

    }

    public function extendRendering( string $widgetContent, Widget_Base $widget = null ): string {

        if ( $widget === null || !$this->isRegisteredTo( $widget->get_name() ) ) {
            return $widgetContent;
        }

        if($widget->get_settings($this->controlToggleId) != true) {
            return $widgetContent;
        }

        $template = new Template( __DIR__ . '/partials/responsive-aspect-ratio.php', [
            'content' => $widgetContent,
            'ratio' => $widget->get_settings($this->controlAspectRatioId),
        ]);
//        die();

        return $template->getRendered();

    }

    public function extendContentTemplate( string $widgetContent, Widget_Base $widget = null ) {

        return '';

    }

}