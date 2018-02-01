<?php
namespace ElebeeCore\Widgets\General\BetterAccordion;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use ElebeeCore\Lib\ElebeeWidget;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Accordion Widget
 */
class BetterAccordion extends ElebeeWidget {

    public function enqueueStyles() {
        // TODO: Implement enqueueStyles() method.
    }

    public function enqueueScripts() {
        // TODO: Implement enqueueScripts() method.
    }

    /**
     * Retrieve accordion widget name.
     *
     * @since 0.1.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'accordion';
    }

    /**
     * Retrieve accordion widget title.
     *
     * @since 0.1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Better Accordion', 'elementor' );
    }

    /**
     * Retrieve accordion widget icon.
     *
     * @since 0.1.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-accordion';
    }

    /**
     * Register accordion widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 0.1.0
     * @access protected
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'section_title',
            [
                'label' => __( 'Accordion', 'elementor' ),
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label' => __( 'Accordion Items', 'elementor' ),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'tab_title' => __( 'Accordion #1', 'elementor' ),
                        'tab_content' => __( 'I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor' ),
                    ],
                    [
                        'tab_title' => __( 'Accordion #2', 'elementor' ),
                        'tab_content' => __( 'I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor' ),
                    ],
                ],
                'fields' => [
                    [
                        'name' => 'tab_title',
                        'label' => __( 'Title & Content', 'elementor' ),
                        'type' => Controls_Manager::TEXT,
                        'default' => __( 'Accordion Title' , 'elementor' ),
                        'label_block' => true,
                    ],
                    [
                        'name' => 'tab_content',
                        'label' => __( 'Content', 'elementor' ),
                        'type' => Controls_Manager::WYSIWYG,
                        'default' => __( 'Accordion Content', 'elementor' ),
                        'show_label' => false,
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->add_control(
            'use_custom_icon',
            [
                'label' => __( 'Custom Icon', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'No',
                'label_on' => __( 'Yes', 'elebee' ),
                'label_off' => __( 'No', 'elebee' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __( 'Accordion Icon', 'elebee' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-plus',
                'include' => [
                    'fa fa-plus',
                    'fa fa-level-down',
                    'fa fa-sort-desc',
                    'fa fa-angle-double-down',
                    'fa fa-angle-down',
                    'fa fa-arrow-circle-down',
                    'fa fa-arrow-circle-o-down',
                    'fa fa-arrow-down',
                    'fa fa-caret-down',
                    'fa fa-chevron-circle-down',
                    'fa fa-chevron-down',
                    'fa fa-hand-o-down',
                    'fa fa-long-arrow-down',
                    'fa fa-toggle-down',
                ],
                'condition' => [
                    'use_custom_icon!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'custom_icon',
            [
                'label' => __( 'Custom Icon', 'elebee' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'use_custom_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'icon_animation',
            [
                'label'       => __( 'Icon Animation', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'default' => ' ',
                'options' => [
                    ' '   => __( 'None', 'elebee' ),
                    'left-90'  => __( 'Turn left 90°', 'elebee' ),
                    'left-180' => __( 'Turn left 180°', 'elebee' ),
                    'right-90' => __( 'Turn right 90°', 'elebee' ),
                    'right-180' => __( 'Turn right 180°', 'elebee' ),
                    'hide-icon' => __( 'Hide', 'elebee' ),
                ],
                'prefix_class' => '',
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Icons size', 'elebee' ),
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
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .custom-icon',
                    '{{WRAPPER}} svg' => 'height: {{SIZE}}{{UNIT}}; width: auto;',
                ],
                'condition' => [
                    'use_custom_icon' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => __( 'Icon Margin', 'elebee' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-better-accordion-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            'section_title_style',
            [
                'label' => __( 'Accordion', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_align',
            [
                'label' => __( 'Icon Alignment', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => is_rtl() ? 'right' : 'left',
                'options' => [
                    'left' => __( 'Left', 'elementor' ),
                    'right' => __( 'Right', 'elementor' ),
                ],
            ]
        );

        $this->add_control(
            'border_width',
            [
                'label' => __( 'Border Width', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-item' => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-accordion .elementor-tab-content' => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-wrapper .elementor-tab-title.elementor-active > span' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'separator_width',
            [
                'label' => __( 'Separator Width', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-item:not(:first-child)' => 'border-top: {{SIZE}}{{UNIT}} solid;',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => __( 'Border Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-item' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-accordion .elementor-tab-content' => 'border-top-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-wrapper .elementor-tab-title.elementor-active > span' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-accordion .elementor-accordion-item:not(:first-child)' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_title',
            [
                'label' => __( 'Title', 'elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_background',
            [
                'label' => __( 'Background', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-accordion .elementor-tab-title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-accordion .elementor-tab-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-accordion .elementor-tab-title svg' => 'stroke: {{VALUE}}; fill: {{VALUE}}',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
            ]
        );

        $this->add_control(
            'tab_active_color',
            [
                'label' => __( 'Active Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-accordion .elementor-tab-title.elementor-active' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-accordion .elementor-tab-title.elementor-active svg' => 'stroke: {{VALUE}}; fill: {{VALUE}}',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-accordion .elementor-tab-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );

        $this->add_control(
            'heading_content',
            [
                'label' => __( 'Content', 'elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_background_color',
            [
                'label' => __( 'Background', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-accordion .elementor-tab-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __( 'Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-accordion .elementor-tab-content' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .elementor-accordion .elementor-tab-content',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render accordion widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 0.1.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings();

        $id_int = substr( $this->get_id_int(), 0, 3 );
        ?>
        <div class="elementor-accordion" role="tablist">
            <?php $counter = 1; ?>
            <?php foreach ( $settings['tabs'] as $item ) :
                $tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $counter - 1 );

                $this->add_render_attribute( $tab_content_setting_key, [
                    'class' => [ 'elementor-tab-content', 'elementor-clearfix' ],
                    'data-tab' => $counter,
                    'role' => 'tabpanel',
                ] );

                $this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
                ?>
                <div class="elementor-accordion-item">
                    <div class="elementor-tab-title" tabindex="<?php echo $id_int . $counter; ?>" data-tab="<?php echo $counter; ?>" role="tab">
						<span class="elementor-better-accordion-icon elementor-accordion-icon-<?php echo $settings['icon_align']; ?>">
							<i class="<?php echo($settings['use_custom_icon'] != 'yes' ? esc_attr( $settings['icon']) : ''  ) ?>">
                                <?php echo($settings['use_custom_icon'] == 'yes' ? $this->inlineSvg($this->get_settings( 'custom_icon' )[url]) : ''  ) ?></i>
						</span>
                        <?php echo $item['tab_title']; ?>
                    </div>
                    <div <?php echo $this->get_render_attribute_string( $tab_content_setting_key ); ?>><?php echo $this->parse_text_editor( $item['tab_content'] ); ?></div>
                </div>
                <?php
                $counter++;
            endforeach;
            ?>
        </div>
        <?php
    }

    function inlineSvg($url) {
        if (end(explode('.', $url)) == 'svg') {
            return file_get_contents($url);
        } else {
            return '<img class="custom-icon" src="' . $url . '">';
        }
    }
}