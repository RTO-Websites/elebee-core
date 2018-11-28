<?php
/**
 * WidgetExtensionAccordion.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Extensions\Accordion
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Extensions/Accordion/WidgetExtensionAccordion.html
 */

namespace ElebeeCore\Elementor\Extensions\Accordion;


use ElebeeCore\Elementor\Extensions\WidgetExtensionBase;
use ElebeeCore\Lib\Elebee;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class WidgetExtensionAccordion extends WidgetExtensionBase {

    /**
     * @since 0.1.0
     * @var string
     */
    private $controlAccordionId;


    /**
     * @since 0.1.0
     * @var string
     */
    private $controlAccordionIconSize;

    /**
     * @since 0.1.0
     * @var string
     */
    private $controlAccordionIconMargin;

    /**
     * @since 0.1.0
     * @var string
     */
    private $controlAccordionIconSwitch;
	
    /**
     * @since 0.1.0
     * @var string
     */
    private $controlAccordionIconAnimation;
	
    /**
     * @since 0.1.0
     * @var string
     */
    private $controlAccordionIconAnimationDuration;

    /**
     * @since 0.1.0
     * @var bool
     */
    private static $enqueueStyles = false;

    /**
     * Accordion constructor.
     *
     * @since 0.1.0
     */
    public function __construct() {

        parent::__construct();
        $this->controlAccordionId = 'elebeeAccordion';
        $this->controlAccordionIconSize = 'elebeeAccordionIconSize';
		$this->controlAccordionIconMargin = 'elebeeAccordionIconMargin';
        $this->controlAccordionIconAnimation = 'elebeeAccordionIconAnimation';
		$this->controlAccordionIconAnimationSwitch = 'elebeeAccordionIconSwitch';
        $this->controlAccordionIconAnimationDuration = 'elebeeAccordionIconAnimationDuration';

		$this->definePublicHooks();
    }

    /**
     * @since 0.2.0
     */
    public function definePublicHooks() {
		$this->getLoader()->addAction( 'elementor/frontend/before_enqueue_scripts', $this, 'enqueueStyles' );
        $this->getLoader()->addAction( 'elementor/frontend/element/before_render', $this, 'setRenderAttributes' );
    }

    public function startControlsSection( Controls_Stack $element ) {

    }

    public function addControls( Controls_Stack $element ) {
        // TODO: implement usage of responsive controls.
		
        $element->add_responsive_control(
            $this->controlAccordionIconSize,
            [
                'label' => __( 'Size', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 16,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],

                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-accordion-icon > i::before' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		

		$element->add_responsive_control(
			$this->controlAccordionIconMargin,
			[
				'label' => __( 'Margin', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-accordion-icon > i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		

		$element->add_control(
			$this->controlAccordionIconAnimationSwitch,
			[
				'label' => __( 'Active Icon', 'elementor' ) . ' ' . __( 'or', 'elementor' ) . ' ' . __( 'Animation', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Icon', 'elementor' ),
				'label_on' => __( 'Animation', 'elementor' ),
				'default' => 'no',
				'selectors' => [
					'{{WRAPPER}} .elementor-accordion-icon-opened' => 'display: none;',
					'{{WRAPPER}} .elementor-accordion-icon-closed' => 'display: block;',
				]
			]
		);
		
        $element->add_control(
            $this->controlAccordionIconAnimation,
            [
                'label' => __( 'Icon Animation', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __( 'None', 'elementor' ),
                    'anti-90' => __( 'Anticlockwise 90°', 'elebee' ),
                    'anti-180' => __( 'Anticlockwise 180°', 'elebee' ),
                    'clockwise-90' => __( 'Clockwise 90°', 'elebee' ),
                    'clockwise-180' => __( 'Clockwise 180°', 'elebee' ),
                ],
                'prefix_class' => 'accordion-icon-animation-',
				'condition' => [
					'elebeeAccordionIconSwitch' => 'yes',
				],
            ]
        );

		$element->add_control(
			$this->controlAccordionIconAnimationDuration,
			[
				'label' => __( 'Animation Duration', 'elementor' ) . ' (ms)',
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'ms' ],
                'default' => [
                    'size' => 500,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 100,
                    ],

                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-accordion-icon > i::before' => 'transition-duration: {{SIZE}}ms;',
                ],
            ]
		);
		
    }

    public function extendRender( string $widgetContent, Widget_Base $widget = null ): string {

        return $widgetContent;

    }

    public function extendContentTemplate( string $widgetContent, Widget_Base $widget = null ): string {

        return $widgetContent;

    }


    /**
     * @since 0.1.0
     *
     * @return void
     */
    public function enqueueStyles() {
        if ( self::$enqueueStyles ) {
            return;
        }
		$realpath    = str_replace('\\', '/', dirname(__FILE__));
		$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $realpath);
        wp_enqueue_style( 'elebee-accordion', 'http://netclient385'. $path . '/css/accordion.css', [], Elebee::VERSION );
        self::$enqueueStyles = true;

    }

    /**
     * @since 0.1.0
     *
     * @param Element_Base $element
     * @return void
     */
    public function setRenderAttributes( Element_Base $element ) {
        if ( $element->get_name() != 'section' ) {
            return;
        }

        if ( $element->get_settings( $this->controlAccordionId ) != true ) {
            return;
        }


        $attributes = [
            'data-elebee-accordion' => '',
        ];

        if ( $element->get_settings( $this->controlAccordionIconSize ) ) {
            $attributes['data-accordion-icon-size'] = '';
        }

        $element->set_render_attribute( '_wrapper', $attributes );

    }

}