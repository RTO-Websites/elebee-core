<?php
/**
 * @since 0.1.0
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Extensions\Sticky;


use ElebeeCore\Extensions\ExtensionBase;
use ElebeeCore\Lib\Elebee;
use Elementor\Controls_Manager;
use Elementor\Element_Base;

class Sticky extends ExtensionBase {

    /**
     * @var string
     */
    private $controlStickyId;

    /**
     * @var string
     */
    private $controlStickyPlaceholderId;

    /**
     * @var string
     */
    private $controlStickyPositionId;

    /**
     * @var string
     */
    private $controlStickyOffsetId;

    /**
     * @var bool
     */
    private static $enqueueScripts = false;

    /**
     * Sticky constructor.
     */
    public function __construct() {

        parent::__construct();
        $this->controlStickyId = 'sticky';
        $this->controlStickyPlaceholderId = 'sticky-placeholder';
        $this->controlStickyPositionId = 'sticky-position';
        $this->controlStickyOffsetId = 'sticky-offset';

    }

    /**
     * @since 0.2.0
     */
    public function defineAdminHooks() {
    }

    /**
     * @since 0.2.0
     */
    public function definePublicHooks() {

        $this->getLoader()->addAction( 'elementor/element/section/section_custom_css/after_section_end', $this, 'extend', 50 );
        $this->getLoader()->addAction( 'elementor/frontend/before_enqueue_scripts', $this, 'enqueueScrips' );
        $this->getLoader()->addAction( 'elementor/frontend/element/before_render', $this, 'setRenderAttributes' );

    }

    /**
     * @param Element_Base $element
     */
    public function extend( Element_Base $element ) {

        // TODO: implement usage of responsive controls.

        $element->start_controls_section( 'section_sticky', [
                'label' => __( 'Sticky', 'elebee' ),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_responsive_control(
            $this->controlStickyId, [
                'label' => __( 'Make this section sticky', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Sticky', 'elementor' ),
                'label_off' => __( 'Default', 'elementor' ),
                'return_value' => 'true',
            ]
        );

        $element->add_responsive_control(
            $this->controlStickyPlaceholderId, [
                'label' => __( 'Add a placeholder', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'label_on' => __( 'Show', 'elementor' ),
                'label_off' => __( 'Hide', 'elementor' ),
                'return_value' => 'true',
                'condition' => [
                    $this->controlStickyId => 'true',
                ],
            ]
        );

        $element->add_responsive_control(
            $this->controlStickyPositionId, [
                'label' => __( 'Position', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'true',
                'label_on' => __( 'Top', 'elebee' ),
                'label_off' => __( 'Bottom', 'elebee' ),
                'return_value' => 'true',
                'condition' => [
                    $this->controlStickyId => 'true',
                ],
            ]
        );

        $element->add_responsive_control(
            $this->controlStickyOffsetId,
            [
                'label' => __( 'Offset', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', '%' ],
                'default' => [
                    'size' => 0,
                ],
                'condition' => [
                    $this->controlStickyId => 'true',
                ],
            ]
        );

        $element->end_controls_section();

    }

    /**
     *
     */
    public function enqueueScrips() {

        if ( !self::$enqueueScripts ) {
            return;
        }

        wp_enqueue_script( 'sticky', __DIR__ . '/js/sticky.js', [], Elebee::VERSION, true );

    }

    /**
     * @param Element_Base $element
     */
    public function setRenderAttributes( Element_Base $element ) {

        if ( $element->get_name() != 'section' ) {
            return;
        }

        if ( $element->get_settings( $this->controlStickyId ) != true ) {
            return;
        }

        self::$enqueueScripts = true;

        $attributes = [
            'data-elebee-sticky' => '',
        ];

        if ( $element->get_settings( $this->controlStickyPlaceholderId ) ) {
            $attributes['data-sticky-placeholder'] = '';
        }

        if ( !$element->get_settings( $this->controlStickyPositionId ) ) {
            $attributes['data-stick-to-bottom'] = '';
        }

        $offset = $element->get_settings( $this->controlStickyOffsetId );
        if ( $offset ) {
            $attributes['data-sticky-offset'] = $offset['size'] . $offset['unit'];
        }

        $element->set_render_attribute( '_wrapper', $attributes );

    }

}