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

use ElebeeCore\Elementor\Widgets\WidgetBase;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Controls_Manager;
use ElebeeCore\Lib\Elebee;
use ElebeeCore\Lib\Util\Template;

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
            'text',
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
            'newsticker_size',
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
            'newsticker_speed',
            [
                'label' => __( 'Speed px/s', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 300,
                    ],
                    'default' => [
                        'px' => 20,
                    ],
                ],
            ]
        );

        $this->add_control(
            'newsticker_repeat',
            [
                'label' => __( 'Repeat', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'Off', 'elementor' ),
                'label_on' => __( 'On', 'elementor' ),
                'value' => 1,
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
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elebee-newsticker-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
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

        if ( empty( $settings['text'] ) ) {
            return;
        }

        $this->add_render_attribute( 'newsticker', 'class', 'elebee-newsticker-content' );

        if ( $settings['newsticker_speed'] ) {
            $this->add_render_attribute( 'newsticker', 'data-pxps', $settings['newsticker_speed']['size'] );
        }

        if ( $settings['newsticker_repeat'] ) {
            $this->add_render_attribute( 'newsticker', 'data-repeat', $settings['newsticker_repeat'] );
            $settings['text'] = sprintf( '<span>%1$s </span><span>%1$s </span>', $settings['text'] );
        } else {
            $this->add_render_attribute( 'newsticker', 'class', 'start-right' );
        }

        $newstickerTemplate = new Template( __DIR__ . '/partials/newsticker.php', [
            'newstickerSize' => $settings['newsticker_size'],
            'newstickerAttributes' => $this->get_render_attribute_string( 'newsticker' ),
            'newstickerText' => $settings['text'],
            'newstickerRepeat' => $settings['newsticker_repeat'],
        ] );
        $newstickerTemplate->render();
    }
}