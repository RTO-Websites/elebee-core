<?php
/**
 * @since   1.0.0
 * @author  hterhoeven
 * @licence MIT
 */

namespace ElebeeCore\Extensions\ResponsiveAspectRatio;


use ElebeeCore\Extensions\WidgetExtensionBase;
use ElebeeCore\Lib\Template;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Widget_Base;

/**
 * Class ResponsiveAspectRatio
 * @package ElebeeCore\Extensions
 */
class ResponsiveAspectRatio extends WidgetExtensionBase {

    private $sectionId;

    private $controlToggleId;

    private $controlAspectRatioId;

    private $controlCustomAspectRatioId;

    public function __construct() {

        parent::__construct();
        $this->sectionId = 'sectionResponsiveAspectRatio';
        $this->controlToggleId = 'aspectRatioToggle';
        $this->controlAspectRatioId = 'aspectRatio';
        $this->controlCustomAspectRatioId = 'customAspectRatio';

    }

    public function startControlsSection( Controls_Stack $element ) {

        $element->start_controls_section(
            $this->sectionId, [
                'label' => __( 'Responsive Aspect Ratio', 'elebee' ),
                'tab' => $this->getTab(),
            ]
        );

    }

    public function addControls( Controls_Stack $element ) {

        $firstControlArgs = [
            'label' => __( 'Aspect Ratio', 'elebee' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __( 'Fix', 'elementor' ),
            'label_off' => __( 'Default', 'elementor' ),
            'return_value' => 'true',
        ];
        if ( $this->isSeparatorBefore() ) {
            $firstControlArgs['separator'] = 'before';
        }


        $element->add_control( $this->controlToggleId, $firstControlArgs );

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
                        'min' => 1.00,
                        'max' => 200.00,
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

    }

    public function extendRender( string $widgetContent, Widget_Base $widget = null ): string {

        if ( $widget === null || !$this->isRegisteredTo( $widget->get_name() ) ) {
            return $widgetContent;
        }

        if ( $widget->get_settings( $this->controlToggleId ) != true ) {
            return $widgetContent;
        }

        $template = new Template( __DIR__ . '/partials/responsive-aspect-ratio.php', [
            'content' => $widgetContent,
            'ratio' => $widget->get_settings( $this->controlAspectRatioId ),
        ] );

        return $template->getRendered();

    }

    public function extendContentTemplate( string $widgetContent, Widget_Base $widget = null ): string {

        return '';

    }

}