<?php
/**
 * WidgetExtensionSticky.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Extensions\Sticky
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Extensions/Sticky/WidgetExtensionSticky.html
 */

namespace ElebeeCore\Elementor\Extensions\Sticky;


use ElebeeCore\Elementor\Extensions\WidgetExtensionBase;
use ElebeeCore\Lib\Elebee;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Widget_Base;

\defined( 'ABSPATH' ) || exit;

/**
 * Class WidgetExtensionSticky
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Extensions\Sticky
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Extensions/Sticky/WidgetExtensionSticky.html
 */
class WidgetExtensionSticky extends WidgetExtensionBase {

    private $sectionId;

    /**
     * @since 0.1.0
     * @var string
     */
    private $controlStickyId;

    /**
     * @since 0.1.0
     * @var string
     */
    private $controlStickyPlaceholderId;

    /**
     * @since 0.1.0
     * @var string
     */
    private $controlStickyPositionId;

    /**
     * @since 0.1.0
     * @var string
     */
    private $controlStickyOffsetId;

    /**
     * @since 0.1.0
     * @var bool
     */
    private static $enqueueScripts = false;

    /**
     * Sticky constructor.
     *
     * @since 0.1.0
     */
    public function __construct() {

        parent::__construct();
        $this->sectionId = 'sectionElebeeSticky';
        $this->controlStickyId = 'elebeeSticky';
        $this->controlStickyPlaceholderId = 'elebeeStickyPlaceholder';
        $this->controlStickyPositionId = 'elebeeStickyPosition';
        $this->controlStickyOffsetId = 'elebeeStickyOffset';

    }

    /**
     * @since 0.2.0
     */
    public function definePublicHooks() {

        parent::definePublicHooks();
        $this->getLoader()->addAction( 'elementor/frontend/before_enqueue_scripts', $this, 'enqueueScrips' );
        $this->getLoader()->addAction( 'elementor/frontend/element/before_render', $this, 'setRenderAttributes' );

    }

    public function startControlsSection( Controls_Stack $element ) {

        $element->start_controls_section(
            $this->sectionId, [
                'label' => __( 'Sticky', 'elebee' ),
                'tab' => $this->getTab(),
            ]
        );

    }

    public function addControls( Controls_Stack $element ) {

        // TODO: implement usage of responsive controls.

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
    public function enqueueScrips() {

        if ( !self::$enqueueScripts ) {
            return;
        }

        wp_enqueue_script( 'sticky', __DIR__ . '/js/sticky.js', [], Elebee::VERSION, true );

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