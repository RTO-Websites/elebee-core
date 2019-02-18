<?php
/**
 * WidgetNewsticker.php
 *
 * @since   0.6.0
 *
 * @package ElebeeCore\Elementor\Widgets\Newsticker
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/Newsticker/WidgetNewsticker.html
 */

namespace ElebeeCore\Elementor\Widgets\Newsticker;

use Elementor\{
    Group_Control_Text_Shadow,
    Group_Control_Typography,
    Controls_Manager
};

use ElebeeCore\{
    Elementor\Widgets\WidgetBase,
    Lib\Elebee,
    Lib\Util\Template
};

\defined( 'ABSPATH' ) || exit;

/**
 * Elementor Newsticker
 *
 * Elementor widget for newsticker.
 *
 * @since   0.6.0
 *
 * @package ElebeeCore\Elementor\Widgets\Newsticker
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/Newsticker/WidgetNewsticker.html
 */
class WidgetNewsticker extends WidgetBase {

    /**
     * @since 0.6.0
     *
     * @return string
     */
    public function get_name(): string {
        return 'newsticker';
    }

    /**
     * @since 0.6.0
     *
     * @return string
     */
    public function get_title(): string {
        return __( 'Newsticker', 'elebee' );
    }

    /**
     * @since 0.6.0
     *
     * @return string
     */
    public function get_icon(): string {
        return 'eicon-animation-text';
    }

    /**
     * @since 0.6.0
     *
     * @return void
     */
    public function enqueueStyles() {
        wp_enqueue_style( $this->get_name(), get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Elementor/Widgets/Newsticker/assets/css/newsticker.css', [], Elebee::VERSION, 'all' );
    }

    /**
     * @since 0.6.0
     *
     * @return void
     */
    public function enqueueScripts() {
        wp_enqueue_script( $this->get_name(), get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Elementor/Widgets/Newsticker/assets/js/newsticker.js', [], Elebee::VERSION, false );
    }

    /**
     * @since 0.6.0
     *
     * @return void
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'newsticker_text',
            [
                'label' => __( 'Display Text', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __( 'Text eingeben ', 'elebee' ),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'newsticker_tag',
            [
                'label' => __( 'HTML Tag', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                ],
                'default' => 'div',
            ]
        );

        $this->add_control(
            'newsticker_play_state',
            [
                'label' => __( 'Pause on Hover', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'newsticker_px_per_secound',
            [
                'label' => __( 'Speed px/s', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'size' => 20,
                ],
            ]
        );

        $this->add_control(
            'newsticker_start_position',
            [
                'label' => __( 'Start Position', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'right' => __( 'Right', 'elementor' ),
                    'left' => __( 'Left', 'elementor' ),
                ],
                'default' => 'left',
            ]
        );

        $this->add_control(
            'newsticker_item_padding',
            [
                'label' => __( 'Item Gap', 'elebee' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem', 'vw' ],
                'allowed_dimensions' => [
                    'right',
                    'left',
                ],
                'default' => [
                    'left' => '10',
                    'right' => '10',
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .elebee-newsticker-content > span > span' => 'padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __( 'Title', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elebee-newsticker-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .elebee-newsticker-content',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'newsticker_shadow',
                'selector' => '{{WRAPPER}} .elebee-newsticker-content',
            ]
        );

        $this->add_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label' => __( 'Blend Mode', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Normal', 'elementor' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elebee-newsticker-content' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @since 0.6.0
     *
     * @return void
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['newsticker_text'] ) ) {
            return;
        }

        $this->add_render_attribute( 'newsticker', 'class', 'elebee-newsticker-content' );
        $this->add_render_attribute( 'newsticker', 'data-px-per-secound', $settings['newsticker_px_per_secound']['size'] );

        if ( 'yes' === $settings['newsticker_play_state'] ) {
            $this->add_render_attribute( 'newsticker', 'class', 'elebee-newsticker-paused' );
        }

        $newstickerTemplate = new Template( __DIR__ . '/partials/newsticker.php', [
            'newstickerTag' => $settings['newsticker_tag'],
            'newstickerAttributes' => $this->get_render_attribute_string( 'newsticker' ),
            'newstickerText' => $settings['newsticker_text'],
            'newstickerStartPosition' => $settings['newsticker_start_position'],
        ] );
        $newstickerTemplate->render();
    }
}