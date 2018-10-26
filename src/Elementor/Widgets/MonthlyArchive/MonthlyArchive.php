<?php
namespace ElebeeCore\Elementor\Widgets\MonthlyArchive;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Text_Shadow;
use ElebeeCore\Elementor\Widgets\WidgetBase;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class MonthlyArchive extends WidgetBase {

    public function __construct( $data = [], $args = null ) {

        parent::__construct( $data, $args );

    }

    public function enqueueStyles() {
        // TODO: Implement enqueueStyles() method.
    }

    public function enqueueScripts() {
        // TODO: Implement enqueueScripts() method.
    }

    /**
     * Retrieve image widget name.
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'monthly_archive';
    }

    /**
     * Retrieve image widget title.
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Monthly Archive', TEXTDOMAIN );
    }

    /**
     * Retrieve image widget icon.
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-post-list';
    }

    /**
     * Register image widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @access protected
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'section_title',
            [
                'label' => __( 'Title', 'elementor' ),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'elementor' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __( 'Enter your title', 'elementor' ),
                'default' => __( 'Add Your Heading Text Here', 'elementor' ),
            ]
        );

        $this->add_control(
            'size',
            [
                'label' => __( 'Size', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __( 'Default', 'elementor' ),
                    'small' => __( 'Small', 'elementor' ),
                    'medium' => __( 'Medium', 'elementor' ),
                    'large' => __( 'Large', 'elementor' ),
                    'xl' => __( 'XL', 'elementor' ),
                    'xxl' => __( 'XXL', 'elementor' ),
                ],
            ]
        );

        $this->add_control(
            'header_size',
            [
                'label' => __( 'HTML Tag', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h2',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'elementor' ),
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
            'view',
            [
                'label' => __( 'View', 'elementor' ),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_year',
            [
                'label' => __( 'Year', TEXTDOMAIN ),
            ]
        );

        $this->add_control(
            'year_size',
            [
                'label' => __( 'Size', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __( 'Default', 'elementor' ),
                    'small' => __( 'Small', 'elementor' ),
                    'medium' => __( 'Medium', 'elementor' ),
                    'large' => __( 'Large', 'elementor' ),
                    'xl' => __( 'XL', 'elementor' ),
                    'xxl' => __( 'XXL', 'elementor' ),
                ],
            ]
        );

        $this->add_control(
            'year_header_size',
            [
                'label' => __( 'HTML Tag', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'year_align',
            [
                'label' => __( 'Alignment', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'elementor' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-year-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'year_view',
            [
                'label' => __( 'View', 'elementor' ),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_month',
            [
                'label' => __( 'Month', TEXTDOMAIN ),
            ]
        );

        $this->add_control(
            'month_size',
            [
                'label' => __( 'Size', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __( 'Default', 'elementor' ),
                    'small' => __( 'Small', 'elementor' ),
                    'medium' => __( 'Medium', 'elementor' ),
                    'large' => __( 'Large', 'elementor' ),
                    'xl' => __( 'XL', 'elementor' ),
                    'xxl' => __( 'XXL', 'elementor' ),
                ],
            ]
        );

        $this->add_control(
            'month_header_size',
            [
                'label' => __( 'HTML Tag', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'div',
            ]
        );

        $this->add_responsive_control(
            'month_align',
            [
                'label' => __( 'Alignment', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'elementor' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-month-link' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'month_view',
            [
                'label' => __( 'View', 'elementor' ),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'traditional',
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
            'title_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .elementor-heading-title',
            ]
        );

        $this->add_control(
            'blend_mode',
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
                    '{{WRAPPER}} .elementor-heading-title' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_year_style',
            [
                'label' => __( 'Year', TEXTDOMAIN ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'year_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .elementor-year-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'year_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-year-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'year_text_shadow',
                'selector' => '{{WRAPPER}} .elementor-year-title',
            ]
        );

        $this->add_control(
            'year_blend_mode',
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
                    '{{WRAPPER}} .elementor-year-title' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_month_style',
            [
                'label' => __( 'Month', TEXTDOMAIN ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_link_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'elementor' ),
            ]
        );

        $this->add_control(
            'month_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .elementor-month-link a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'elementor' ),
            ]
        );

        $this->add_control(
            'month_color_hover',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .elementor-month-link a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_active',
            [
                'label' => __( 'Active', 'elementor' ),
            ]
        );

        $this->add_control(
            'month_color_active',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .elementor-month-link a:active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_visited',
            [
                'label' => __( 'Visited', 'elementor' ),
            ]
        );

        $this->add_control(
            'month_color_visited',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .elementor-month-link a:visited' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'month_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-month-link a',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'month_text_shadow',
                'selector' => '{{WRAPPER}} .elementor-month-link a',
            ]
        );

        $this->add_control(
            'month_blend_mode',
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
                    '{{WRAPPER}} .elementor-month-link a' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render image widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( !empty( $settings['title'] ) ) {
            $this->add_render_attribute( 'title', 'class', 'elementor-heading-title' );

            if ( ! empty( $settings['size'] ) ) {
                $this->add_render_attribute( 'title', 'class', 'elementor-size-' . $settings['size'] );
            }

            $this->add_inline_editing_attributes( 'title' );

            $title = $settings['title'];

            $title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'title' ), $title );

            echo $title_html;
        }

        $this->add_render_attribute( 'title_year', 'class', 'elementor-year-title' );

        if ( ! empty( $settings['year_size'] ) ) {
            $this->add_render_attribute( 'title_year', 'class', 'elementor-size-' . $settings['year_size'] );
        }

        $this->add_render_attribute( 'text_month', 'class', 'elementor-month-link' );

        if ( ! empty( $settings['month_size'] ) ) {
            $this->add_render_attribute( 'text_month', 'class', 'elementor-size-' . $settings['month_size'] );
        }

        global $wpdb, $wp_locale;
        $query = "SELECT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, count(ID) as posts FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC";
        $arcresults = $wpdb->get_results($query);
        if ( $arcresults ) {
            $year = null;
            $i = 0;
            foreach ( (array) $arcresults as $arcresult ) {
                if($arcresult->year != $year) {
                    $year_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['year_header_size'],$this->get_render_attribute_string( 'title_year' ), $arcresult->year );
                    echo $year_html . '<br>';
                    $year = $arcresult->year;
                }
                $url = get_month_link( $arcresult->year, $arcresult->month );
                $text = sprintf(__('%1$s %2$d'), $wp_locale->get_month($arcresult->month), $arcresult->year);
                $after = '&nbsp;('.$arcresult->posts.')';
                $this->add_render_attribute( 'url'.$i, 'href', $url );

                $month = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url'.$i ), $text.$after );
                $month_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['year_header_size'], $this->get_render_attribute_string( 'text_month' ), $month);
                echo $month_html;
                $i++;
            }
        }
    }
}
