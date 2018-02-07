<?php
/**
 * BigAndSmallImageWithDescription.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Widgets\Exclusive\BigAndSmallImageWithDescription
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Widgets/Exclusive/BigAndSmallImageWithDescription/BigAndSmallImageWithDescription.html
 */

namespace ElebeeCore\Widgets\Exclusive\BigAndSmallImageWithDescription;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use ElebeeCore\Lib\Template;

defined( 'ABSPATH' ) || exit;

/**
 * Class BigAndSmallImageWithDescription
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Widgets\Exclusive\BigAndSmallImageWithDescription
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Widgets/Exclusive/BigAndSmallImageWithDescription/BigAndSmallImageWithDescription.html
 */
class BigAndSmallImageWithDescription extends Widget_Base {

    /**
     * @since 0.1.0
     *
     * @return string
     */
    public function get_name(): string {

        return 'big-and-small-image-with-description';

    }

    /**
     * @since 0.1.0
     *
     * @return string|void
     */
    public function get_title(): string {

        return __( 'Big and small image with description', 'elebee' );

    }

    /**
     * @since 0.1.0
     *
     * @return array
     */
    public function get_categories(): array {

        return [ 'rto-elements-exclusive' ];

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    protected function _register_controls() {

        $this->start_controls_section(
            'default',
            [
                'label' => __( 'Big and small image with description', 'elebee' ),
            ]
        );

        $this->add_control(
            'orientation',
            [
                'label' => __( 'Orientation', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'big-small',
                'options' => [
                    'big-small' => __( 'Big/Small', 'elebee' ),
                    'small-big' => __( 'Small/Big', 'elebee' ),
                ],
            ]
        );

        $this->add_control(
            'big-image',
            [
                'label' => __( 'Large image', 'elebee' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'small-image',
            [
                'label' => __( 'Small image', 'elebee' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'elebee' ),
                'type' => Controls_Manager::WYSIWYG,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_gallery_images',
            [
                'label' => __( 'Big and small image with description', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'spacing-middle',
            [
                'label' => __( 'Spacing middle', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .big-small .big-image' => 'padding-right: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .big-small .small-image' => 'padding-left: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .small-big .big-image' => 'padding-left: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .small-big .small-image' => 'padding-right: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_responsive_control(
            'spacing-text',
            [
                'label' => __( 'Spacing text', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .small-image img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => __( 'Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .small-image div' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => __( 'Typography', 'elebee' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            ]
        );

        $this->end_controls_section();

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    protected function render() {

        $settings = $this->get_settings();
        $bigImage = new Template( __DIR__ . '/partials/big-image.php', [ 'image' => $settings['big-image'] ] );
        $smallImage = new Template( __DIR__ . '/partials/small-image.php', [ 'image' => $settings['small-image'], 'desc' => $settings['description'] ] );
        $content = '';
        switch ( $settings['orientation'] ) {
            case 'small-big':
                $content = $smallImage->getRendered() . $bigImage->getRendered();
                break;
            default:
                $content = $bigImage->getRendered() . $smallImage->getRendered();
        }
        $wrap = new Template( __DIR__ . '/partials/wrap.php', [ 'orientation' => $settings['orientation'], 'content' => $content ] );
        $wrap->render();

    }

}