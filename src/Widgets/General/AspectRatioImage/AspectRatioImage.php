<?php
/**
 * AspectRatioImage.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Widgets\General\AspectRatioImage
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Widgets/General/AspectRatioImage/AspectRatioImage.html
 */

namespace ElebeeCore\Widgets\General\AspectRatioImage;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use ElebeeCore\Lib\ElebeeWidget;
use ElebeeCore\Lib\Template;

defined( 'ABSPATH' ) || exit;

/**
 * Image Widget
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Widgets\General\AspectRatioImage
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Widgets/General/AspectRatioImage/AspectRatioImage.html
 */
class AspectRatioImage extends ElebeeWidget {

    /**
     * @since 0.1.0
     */
    public function enqueueStyles() {
        // TODO: Implement enqueueStyles() method.
    }

    /**
     * @since 0.1.0
     */
    public function enqueueScripts() {
        // TODO: Implement enqueueScripts() method.
    }

    /**
     * Retrieve image widget name.
     *
     * @since 0.1.0
     */
    public function get_name(): string {

        return 'aspect_image_image';

    }

    /**
     * Retrieve image widget title.
     *
     * @since 0.1.0
     */
    public function get_title(): string {

        return __( 'Aspect Ratio Image', 'elebee' );

    }

    /**
     * Retrieve image widget icon.
     *
     * @since 0.1.0
     */
    public function get_icon(): string {

        return 'eicon-insert-image';

    }

    /**
     * Register image widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 0.1.0
     */
    protected function _register_controls() {

        $this->start_controls_section(
            'section_image',
            [
                'label' => __( 'Image', 'elebee' ),
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __( 'Choose Image', 'elebee' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image', // Actually its `image_size`.
                'label' => __( 'Image Size', 'elebee' ),
                'default' => 'large',
            ]
        );

        $this->add_responsive_control(
            'aspect-ratio',
            [
                'label' => __( 'Image Aspect Ratio', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                        'min' => 25.00,
                        'max' => 400.00,
                    ],
                ],
                'tablet_range' => [
                    '%' => [
                        'min' => 25.00,
                        'max' => 400.00,
                    ],
                ],
                'mobile_range' => [
                    '%' => [
                        'min' => 25.00,
                        'max' => 400.00,
                    ],
                ],
                'default' => [
                    'size' => 56.25,
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'size' => 56.25,
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'size' => 56.25,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rto-image-ratio-container' => 'padding-top: {{SIZE}}%;',
                ],
            ]
        );

        $this->add_control(
            'link_to',
            [
                'label' => __( 'Link to', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __( 'None', 'elebee' ),
                    'file' => __( 'Media File', 'elebee' ),
                    'custom' => __( 'Custom URL', 'elebee' ),
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Link to', 'elebee' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'http://your-link.com', 'elebee' ),
                'condition' => [
                    'link_to' => 'custom',
                ],
                'show_label' => false,
            ]
        );

        $this->add_control(
            'open_lightbox',
            [
                'label' => __( 'Lightbox', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __( 'Default', 'elebee' ),
                    'yes' => __( 'Yes', 'elementor' ),
                    'no' => __( 'No', 'elementor' ),
                ],
                'condition' => [
                    'link_to' => 'file',
                ],
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

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __( 'Image', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'opacity',
            [
                'label' => __( 'Opacity (%)', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .rto-image-ratio-container .image' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => __( 'Hover Animation', 'elebee' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => __( 'Image Border', 'elebee' ),
                'selector' => '{{WRAPPER}} .rto-image-ratio-container .image',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'elebee' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .rto-image-ratio-container .image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .rto-image-ratio-container .image',
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Render image widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 0.1.0
     *
     * @return void
     */
    protected function render() {

        $settings = $this->get_settings();

        if ( empty( $settings['image']['url'] ) ) {
            return;
        }

        $this->add_render_attribute( 'wrapper', 'class', 'elementor-image' );


        $link = $this->get_link_url( $settings );

        if ( $link ) {
            $this->add_render_attribute( 'link', [
                'href' => $link['url'],
                'class' => 'elementor-clickable',
                'data-elementor-open-lightbox' => $settings['open_lightbox'],
            ] );

            if ( !empty( $link['is_external'] ) ) {
                $this->add_render_attribute( 'link', 'target', '_blank' );
            }

            if ( !empty( $link['nofollow'] ) ) {
                $this->add_render_attribute( 'link', 'rel', 'nofollow' );
            }
        }

        $aspectRatioImageTemplate = new Template( __DIR__ . '/partials/aspect-ratio-image.php', [
            'link' => $link,
            'linkAttributes' => $this->get_render_attribute_string( 'link' ),
            'backgroundImage' => $settings['image']['url'],
        ] );
        $aspectRatioImageTemplate->render();

    }

    /**
     * Retrieve image widget link URL.
     *
     * @param object $instance
     *
     * @return array|string|false An array/string containing the link URL, or false if no link.
     */
    private function get_link_url( $instance ) {

        if ( 'none' === $instance['link_to'] ) {
            return false;
        }

        if ( 'custom' === $instance['link_to'] ) {
            if ( empty( $instance['link']['url'] ) ) {
                return false;
            }
            return $instance['link'];
        }

        return [
            'url' => $instance['image']['url'],
        ];

    }

}
