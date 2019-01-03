<?php
/**
 * WidgetPostTypeArchive.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\PostTypeArchive
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/PostTypeArchive/WidgetPostTypeArchive.html
 */

namespace ElebeeCore\Elementor\Widgets\PostTypeArchive;


use ElebeeCore\Elementor\Widgets\WidgetBase;
use ElebeeCore\Lib\Elebee;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use ElebeeCore\Lib\Util\Template;
use WP_Query;

\defined( 'ABSPATH' ) || exit;

/**
 * Class WidgetPostTypeArchive
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\PostTypeArchive
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/PostTypeArchive/WidgetPostTypeArchive.html
 */
class WidgetPostTypeArchive extends WidgetBase {

    /**
     * @since 0.1.0
     * @var array
     */
    protected $postTypes;

    /**
     * @since 0.1.0
     * @var array
     */
    protected $taxonomies;

    /**
     * @var
     */
    protected $terms;

    /**
     * @since 0.1.0
     * @var bool
     */
    protected $isForArchive;

    /**
     * @since 0.1.0
     *
     * @return string
     */
    public function get_name(): string {

        return 'post-type-archive';

    }

    /**
     * @since 0.1.0
     * @return string
     */
    public function get_title(): string {

        return __( 'Post type archive', 'elebee' );

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
    public function enqueueStyles() {

        wp_enqueue_style( $this->get_name(), get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Elementor/Widgets/PostTypeArchive/assets/css/' . $this->get_name() . '.css', [], Elebee::VERSION );

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public function enqueueScripts() {
        // TODO: Implement enqueueScripts() method.
    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    protected function _register_controls() {
        global $sitepress;
        if ( !empty( $sitepress ) ) {
            $currentLang = $sitepress->get_current_language();
            $sitepress->switch_lang( $sitepress->get_default_language() );
        }

        $this->postTypes = $this->getPostTypes();
        $this->taxonomies = get_taxonomies( null, 'objects' );
        $this->isForArchive = $this->isForArchive();

        $this->start_controls_section(
            'archive_section',
            [
                'label' => __( 'Archive settings', 'elebee' ),
            ]
        );

        $this->add_control(
            'type',
            [
                'label' => __( 'Post type', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'post',
                'options' => $this->postTypes,
            ]
        );

        $this->addTaxonomyControls();

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image', // Actually its `image_size`.
                'label' => __( 'Image Size', 'elementor' ),
                'default' => 'large',
            ]
        );

        $this->add_control(
            'count',
            [
                'label' => __( 'Number of results', 'elebee' ),
                'type' => Controls_Manager::NUMBER,
                'default' => -1,
            ]
        );

        $this->add_responsive_control(
            'per_row',
            [
                'label' => __( 'Per row', 'elebee' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'default' => 4,
                'selectors' => [
                    '{{WRAPPER}} .post-type-archive-figure' => 'width: calc(100% / {{VALUE}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'horizontal_spacing',
            [
                'label' => __( 'Horizontal spacing', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .post-type-archive-figure' => 'padding: 0 {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .post-type-archive-wrap' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'vertical_spacing',
            [
                'label' => __( 'Vertical spacing', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .post-type-archive-figure' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .post-type-archive-wrap' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'effect',
            [
                'label' => __( 'Effect', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'effect-grayscale',
                'options' => [
                    'effect-grayscale' => __( 'Grayscale', 'elebee' ),
                ],
            ]
        );

        $this->add_control(
            'grayscale-slide-color',
            [
                'label' => __( 'Slide color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'effect-grayscale',
                'condition' => [
                    'effect' => 'effect-grayscale',
                ],
                'selectors' => [
                    '{{WRAPPER}} a::after' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'transition',
            [
                'label' => __( 'Transition', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 's' ],
                'range' => [
                    's' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'default' => [
                    'size' => 0.6,
                    'unit' => 's',
                ],
                'selectors' => [
                    '{{WRAPPER}} .effect-grayscale a::after' => 'transition: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .effect-grayscale figure' => 'transition: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .effect-grayscale svg *' => 'transition: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'archive_style',
            [
                'label' => __( 'Post', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => __( 'Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'comment_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->end_controls_section();

        if ( $this->isForArchive ) {
            $this->start_controls_section(
                'archive_divider',
                [
                    'label' => __( 'Divider', 'elebee' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'divider_style',
                [
                    'label' => __( 'Style', 'elebee' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'solid',
                    'options' => [
                        'solid' => __( 'Solid', 'elebee' ),
                        'dashed' => __( 'Dashed', 'elebee' ),
                        'dotted' => __( 'Dotted', 'elebee' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .post-type-archive-divider' => 'border-top-style: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'divider_width',
                [
                    'label' => __( 'Width', 'elebee' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10,
                        ],
                    ],
                    'default' => [
                        'size' => 4,
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .post-type-archive-divider' => 'border-top-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'divider_color',
                [
                    'label' => __( 'Color', 'elebee' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .post-type-archive-divider' => 'border-top-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'divider_spacing',
                [
                    'label' => __( 'Spacing', 'elebee' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 2,
                            'max' => 50,
                        ],
                    ],
                    'default' => [
                        'size' => 4,
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .post-type-archive-divider' => 'margin: {{SIZE}}{{UNIT}} 0;',
                    ],
                ]
            );

            $this->end_controls_section();

            $this->start_controls_section(
                'more_link',
                [
                    'label' => __( 'More link', 'elebee' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'more_color',
                [
                    'label' => __( 'Color', 'elebee' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPPER}} .link a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'more_link',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                    'selector' => '{{WRAPPER}} .link a',
                ]
            );

            $this->end_controls_section();
        }

        if ( !empty( $sitepress ) ) {
            $sitepress->switch_lang( $currentLang );
        }
    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    private function addTaxonomyControls() {

        foreach ( $this->postTypes as $typeName => $postType ) {
            $taxonomies = $this->getTaxonomiesForPostType( $typeName );
            if ( empty( $taxonomies ) ) {
                continue;
            }

            $this->add_control(
                'filter_' . $typeName,
                [
                    'label' => __( 'Filter', 'elebee' ),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'type' => $typeName,
                    ],
                ]
            );

            $this->add_control(
                'type_' . $typeName . '_taxonomy',
                [
                    'label' => __( 'Taxonomy', 'elebee' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => array_keys( array_slice( $taxonomies, 0, 1 ) )[0],
                    'options' => $taxonomies,
                    'condition' => [
                        'type' => $typeName,
                        'filter_' . $typeName => 'yes',
                    ],
                ]
            );
            $this->addTermControls( $taxonomies, $typeName );
        }

    }

    /**
     * @since 0.1.0
     *
     * @param array $taxonomies
     * @param string $postType
     * @return void
     */
    private function addTermControls( array $taxonomies, string $postType ) {

        foreach ( $taxonomies as $taxName => $name ) {
            $terms = $this->getTermsForTaxonomy( $taxName );

            $this->add_control(
                'type_' . $postType . '_taxonomy_' . $taxName . '_term',
                [
                    'label' => __( 'Term', 'elebee' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => array_keys( array_slice( $terms, 0, 1 ) )[0],
                    'options' => $terms,
                    'condition' => [
                        'type' => $postType,
                        'type_' . $postType . '_taxonomy' => $taxName,
                        'filter_' . $postType => 'yes',
                    ],
                ]
            );
        }

    }

    /**
     * @since 0.1.0
     *
     * @return array
     */
    private function getPostTypes(): array {

        $tmp = get_post_types( null, 'objects' );
        $types = [];

        foreach ( $tmp as $name => $type ) {
            $types[$name] = $type->labels->name;
        }
        return $types;

    }

    /**
     * @since 0.1.0
     *
     * @param string $name
     * @return array
     */
    private function getTaxonomiesForPostType( string $name ): array {

        $taxonomies = [];

        foreach ( $this->taxonomies as $taxName => $taxonomy ) {
            if ( in_array( $name, $taxonomy->object_type ) && !empty( $this->getTermsForTaxonomy( $taxName ) ) ) {
                $taxonomies[$taxName] = $taxonomy->labels->singular_name;
            }
        }
        return $taxonomies;

    }

    /**
     * @since 0.1.0
     *
     * @param $name
     * @return array
     */
    private function getTermsForTaxonomy( string $name ): array {

        $terms = [];
        $tmpTerms = get_terms( [ 'taxonomy' => $name, 'hide_empty' => false ] );
        foreach ( $tmpTerms as $term ) {
            if ( $term->taxonomy === $name ) {
                $terms[$term->term_id] = $term->name;
            }
        }
        return $terms;

    }

    /**
     * @since 0.1.0
     *
     * @return bool
     */
    protected function isForArchive(): bool {

        if ( is_archive() ) {
            return true;
        }

        if ( is_admin() ) { // will be true (in this method) for preview mode
            $stylePressStyle = get_option( 'dtbaker-elementor' );
            if ( !empty( $stylePressStyle ) && in_array( get_the_ID(), [ $stylePressStyle['defaults']['category'], $stylePressStyle['defaults']['archive'] ] ) ) {
                return true;
            }
        }

        return false;

    }

    /**
     * @since 0.1.0
     *
     * @return array
     */
    protected function getArchiveSettings(): array {

        return [
            'posts_per_page' => -1,
        ];

    }

    /**
     * @since 0.1.0
     *
     * @param $postType
     * @return array
     */
    protected function getQuerySettings( $postType ): array {

        $settings = $this->get_settings();
        $args = [
            'post_type' => $postType,
            'posts_per_page' => $settings['count'],
        ];
        if ( isset( $settings['filter_' . $postType] ) && 'yes' === $settings['filter_' . $postType] ) {
            $taxonomy = $settings['type_' . $postType . '_taxonomy'];
            $terms = apply_filters( 'wpml_object_id', $settings['type_' . $postType . '_taxonomy_' . $taxonomy . '_term'], $taxonomy );
            $args['tax_query'] = [
                [
                    'taxonomy' => $taxonomy,
                    'field' => 'id',
                    'terms' => $terms,
                ],
            ];
        }
        return $args;

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    protected function render() {
        global $sitepress;
        if ( !empty( $sitepress ) ) { // temporarily change the current language to the current post language
            $currentLang = $sitepress->get_current_language();
            $sitepress->switch_lang( apply_filters( 'wpml_post_language_details', null, get_the_ID() )['language_code'] );
        }

        $settings = $this->get_settings();
        $postType = $settings['type'];
        $this->isForArchive = $this->isForArchive();

        $args = $this->getQuerySettings( $postType );
        if ( $this->isForArchive ) {
            $args = wp_parse_args( $this->getArchiveSettings(), $args );
        }

        $q = new WP_Query( $args );

        if ( $q->have_posts() ) {
            $content = '';
            // TODO: make this less ugly. Use Skins
            if ( !$this->isForArchive ) {
                $contentRenderer = new Template( __DIR__ . '/partials/figure.php', [ 'size' => $settings['image_size'] ] );
            } else {
                $contentRenderer = new Template( __DIR__ . '/partials/excerpt.php' );
            }
            while ( $q->have_posts() ) {
                $q->the_post();
                if ( !$this->isForArchive ) {
                    switch ( $settings['effect'] ) {
                        default:
                            $this->setRendererVarsForGrayscale( $contentRenderer );
                            break;
                    }
                } else {
                    $this->setRendererVarsForExcerpts( $contentRenderer );
                }
                $content .= $contentRenderer->getRendered();
            }
            wp_reset_postdata();
            $wrapper = new Template( __DIR__ . '/partials/wrap.php', [ 'content' => $content ] );
            if ( !$this->isForArchive ) {
                $wrapper->setVar( 'effectClass', $settings['effect'] );
            } else {
                $wrapper->setVar( 'effectClass', 'in-archive' );
            }
            $wrapper->render();
        }

        if ( !empty( $sitepress ) ) {
            $sitepress->switch_lang( $currentLang );
        }

    }

    /**
     * @since 0.1.0
     *
     * @param Template $renderer
     * @return void
     */
    protected function setRendererVarsForGrayscale( Template &$renderer ) {

        $imageID = get_post_meta( get_the_ID(), 'additional-image', true );
        $imagePath = get_attached_file( $imageID );
        if ( 'svg' === substr( $imagePath, -3 ) ) {
            $image = file_get_contents( $imagePath );
        } else {
            $image = wp_get_attachment_image(
                $imageID,
                null,
                null,
                [
                    'class' => 'post-type-archive-logo',
                ]
            );
        }

        $renderer->setVar( 'figureContent', $image );

    }

    /**
     * @since 0.1.0
     *
     * @param Template $renderer
     * @return void
     */
    protected function setRendererVarsForExcerpts( Template &$renderer ) {

        $renderer->setVar( 'title', get_the_title() );
        $renderer->setVar( 'excerpt', get_the_excerpt() );
        $renderer->setVar( 'link', get_the_permalink() );

    }
}