<?php
/**
 * ResponsiveAspectRatio.php
 *
 * @since   0.3.2
 *
 * @package ElebeeCore\Extensions\ResponsiveAspectRatio
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Extensions/ResponsiveAspectRatio/ResponsiveAspectRatio.html
 */

namespace ElebeeCore\Extensions\ResponsiveAspectRatio;


use ElebeeCore\Extensions\WidgetExtensionBase;
use ElebeeCore\Lib\Util\Template;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Widget_Base;

/**
 * Class ResponsiveAspectRatio
 *
 * @since   0.3.2
 *
 * @package ElebeeCore\Extensions\ResponsiveAspectRatio
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Extensions/ResponsiveAspectRatio/ResponsiveAspectRatio.html
 */
class ResponsiveAspectRatio extends WidgetExtensionBase {

    /**
     * @since 0.3.2
     * @var string
     */
    private $sectionId;

    /**
     * @since 0.3.2
     * @var string
     */
    private $controlToggleId;

    /**
     * @since 0.3.2
     * @var string
     */
    private $controlAspectRatioId;

    /**
     * @since 0.3.2
     * @var string
     */
    private $controlCustomAspectRatioId;

    /**
     * ResponsiveAspectRatio constructor.
     *
     * @since 0.3.2
     */
    public function __construct() {

        parent::__construct();
        $this->sectionId = 'sectionResponsiveAspectRatio';
        $this->controlToggleId = 'aspectRatioToggle';
        $this->controlAspectRatioId = 'aspectRatio';
        $this->controlCustomAspectRatioId = 'customAspectRatio';

    }

    /**
     * @since 0.3.2
     */
    public function startControlsSection( Controls_Stack $element ) {

        $element->start_controls_section(
            $this->sectionId, [
                'label' => __( 'Responsive Aspect Ratio', 'elebee' ),
                'tab' => $this->getTab(),
            ]
        );

    }

    /**
     * @since 0.3.2
     */
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

    /**
     * @since 0.3.2
     */
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

    /**
     * @since 0.3.2
     */
    public function extendContentTemplate( string $widgetContent, Widget_Base $widget = null ): string {

        return '';

    }

}