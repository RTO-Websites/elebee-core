<?php
/**
 * WidgetImprint.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\Imprint
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/Imprint/WidgetImprint.html
 */

namespace ElebeeCore\Elementor\Widgets\Imprint;


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
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\Imprint
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/Imprint/WidgetImprint.html
 */
class WidgetImprint extends WidgetBase {

    /**
     * Imprint constructor.
     *
     * @since 0.1.0
     *
     * @param array $data
     * @param array $args
     */
    public function __construct( array $data = [], array $args = null ) {

        parent::__construct( $data, $args );
        $this->album = null;

    }

    /**
     * @since 0.1.0
     */
    public function enqueueStyles() {

        wp_enqueue_style( $this->get_name(), get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Elementor/Widgets/Imprint/assets/css/imprint.css', [], Elebee::VERSION, 'all' );

    }

    /**
     * @since 0.1.0
     */
    public function enqueueScripts() {
        // TODO: Implement enqueueScripts() method.
    }

    /**
     * Retrieve the widget name.
     *
     * @since  0.1.0
     */
    public function get_name(): string {

        return 'imprint';

    }

    /**
     * Retrieve the widget title.
     *
     * @since  0.1.0
     */
    public function get_title(): string {

        return __( 'Imprint', 'elebee' );

    }

    /**
     * Retrieve the widget icon.
     *
     * @since  0.1.0
     */
    public function get_icon(): string {

        return 'eicon-posts-ticker';

    }

    /**
     * Retrieve the list of scripts the widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 0.1.0
     */
    public function get_script_depends(): array {

        return [ 'imprint' ];

    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  0.1.0
     *
     * @return void
     */
    protected function _register_controls() {

        $this->registerSectionContent();
        $this->registerSectionTitleStyle();
        $this->registerSectionTextStyle();
        $this->registerSectionLinkStyle();

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    private function registerSectionContent() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Imprint', 'elebee' ),
            ]
        );

        $this->add_control(
            'title_options',
            [
                'label' => __( 'Titel', 'elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'elebee' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Impressum', 'elebee' ),
            ]
        );

        $this->add_control(
            'header_size',
            [
                'label' => __( 'HTML Tag', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => __( 'H1', 'elebee' ),
                    'h2' => __( 'H2', 'elebee' ),
                    'h3' => __( 'H3', 'elebee' ),
                    'h4' => __( 'H4', 'elebee' ),
                    'h5' => __( 'H5', 'elebee' ),
                    'h6' => __( 'H6', 'elebee' ),
                    'div' => __( 'div', 'elebee' ),
                    'span' => __( 'span', 'elebee' ),
                    'p' => __( 'p', 'elebee' ),
                ],
                'default' => 'h2',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'elebee' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'elebee' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'elebee' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'elebee' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'elebee' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'imprint_options',
            [
                'label' => __( 'Impressum', 'elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'text',
            [
                'label' => __( 'Text', 'elebee' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => ( new Template( __DIR__ . '/partials/text-default.php' ) )->getRendered(),
            ]
        );

        $this->add_control(
            'view',
            [
                'label' => __( 'View', 'elebee' ),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->end_controls_section();

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    private function registerSectionTitleStyle() {

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __( 'Title', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->end_controls_section();

    }

    /**
     *
     *
     * @since 0.1.0
     *
     * @return void
     */
    private function registerSectionTextStyle() {

        $this->start_controls_section(
            'section_text_style',
            [
                'label' => __( 'Text', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'h_tag_color',
            [
                'label' => __( 'H-Tag Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .text h2' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .text h3' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .text h4' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .text h5' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .text h6' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .text',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_text_shadow',
                'selector' => '{{WRAPPER}} .text',
            ]
        );

        $this->end_controls_section();

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    private function registerSectionLinkStyle() {

        $this->start_controls_section(
            'section_link_style',
            [
                'label' => __( 'Links', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_link_style' );
        $this->start_controls_tab(
            'tab_link',
            [
                'label' => __( 'Normal', 'elebee' ),
            ]
        );
        $this->add_control(
            'link_color',
            [
                'label' => __( 'Link Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_link_hover',
            [
                'label' => __( 'Hover', 'elebee' ),
            ]
        );
        $this->add_control(
            'link_color_hover',
            [
                'label' => __( 'Link Color Hover', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_hover_time',
            [
                'label' => __( 'Speed (ms)', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'ms' ],
                'range' => [
                    'ms' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 600,
                    'unit' => 'ms',
                ],
                'selectors' => [
                    '{{WRAPPER}} a' => 'transition-duration: {{SIZE}}ms;',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'link_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .text a',
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'link_text_shadow',
                'selector' => '{{WRAPPER}} .text a',
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  0.1.0
     *
     * @return void
     */
    protected function render() {

        $settings = $this->get_settings();

        if ( empty( $settings['title'] ) ) {
            return;
        }

        $this->add_render_attribute( 'title', 'class', 'elementor-heading-title' );
//		$this->add_inline_editing_attributes( 'title' );

        $imprintTemplate = new Template( __DIR__ . '/partials/imprint.php', [
            'title' => $settings['title'],
            'headerSize' => $settings['header_size'],
            'headerAttributes' => $this->get_render_attribute_string( 'heading' ),
            'text' => $settings['text'],
        ] );
        $imprintTemplate->render();
    }

    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since  0.1.0
     *
     * @return void
     */
    protected function _content_template() {

        $contentTemplate = new Template( __DIR__ . '/partials/editor-content.php' );
        $contentTemplate->render();

    }

}
