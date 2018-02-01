<?php

namespace ElebeeCore\Skins;

use Elementor\Group_Control_Typography;
use ElementorPro\Modules\Posts\Skins\Skin_Base;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;

class SkinArchive extends Skin_Base {

    public function get_id() {

        return 'rto';

    }

    public function get_title() {

        return __( 'RTO', 'elebee' );

    }

    protected function _register_controls_actions() {

        add_action( 'elementor/element/posts/section_layout/before_section_end', [ $this, 'register_controls' ] );
        add_action( 'elementor/element/posts/section_query/after_section_end', [ $this, 'register_style_sections' ] );
        add_action( 'elementor/element/posts/section_pagination/after_section_end', [ $this, 'registerArchiveSection' ] );
//        add_action( 'elementor/element/posts/section_design_content/after_section_end', [ $this, 'registerArchiveStyleSection' ] );

    }

    public function registerArchiveSection( Widget_Base $widget ) {

        $this->start_controls_section(
            'section_archive',
            [
                'label' => __( 'Archive', 'elebee' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'add_archive_link',
            [
                'label' => __( 'Add archive link', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __( 'No', 'elebee' ),
                    'below' => __( 'Below', 'elebee' ),
                    'above' => __( 'Above', 'elebee' ),
                    'both' => __( 'Both', 'elebee' ),
                ],
            ]
        );

        $this->add_control(
            'single_text',
            [
                'label' => __( 'Single category text', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Show all posts in the category %1$s.',
            ]
        );

        $this->add_control(
            'multi_text',
            [
                'label' => __( 'Multiple category text', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Show all posts in the category:',
            ]
        );

        $this->end_controls_section();

        $this->registerArchiveStyleSection(); // TODO: try doing this per hook like in the commented line above.

    }

    public function registerArchiveStyleSection() {

        $this->start_controls_section(
            'section_archive_design',
            [
                'label' => __( 'Archive', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'archive_color',
            [
                'label' => __( 'Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .link-to-archive' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'archive_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .link-to-archive',
            ]
        );

        $this->end_controls_section();

    }

    protected function render_post() {

        $this->render_post_header();
        $this->render_thumbnail();
        $this->render_text_header();
        $this->render_meta_data();
        $this->render_title();
        $this->render_excerpt();
        $this->render_read_more();
        $this->render_text_footer();
        $this->render_post_footer();

    }

    protected function render_loop_header() {

        $this->render_archive_link( 'above' );
        parent::render_loop_header();

    }

    protected function render_loop_footer() {

        parent::render_loop_footer();
        $this->render_archive_link( 'below' );

    }

    private function render_archive_link( $where ) {

        $parentSettings = $this->parent->get_settings();
        $showArchiveLink = in_array( $parentSettings[$this->get_id() . '_add_archive_link'], [ $where, 'both' ] );
        if ( $showArchiveLink && is_array( $parentSettings['posts_category_ids'] ) ) {
            if ( count( $parentSettings['posts_category_ids'] ) === 1 ) {
                $catId = array_pop( $parentSettings['posts_category_ids'] );
                printf(
                    '<a href="%1$s" class="link-to-archive">%2$s</a>',
                    get_category_link( $catId ),
                    sprintf(
                        $parentSettings[$this->get_id() . '_single_text'],
                        get_cat_name( $catId )
                    )
                );
            } else {
                echo $parentSettings[$this->get_id() . '_multi_text'];
                echo '<ul>';
                foreach ( $parentSettings['posts_category_ids'] as $catId ) {
                    printf(
                        '<li><a href="%1$s" class="link-to-archive">%2$s</a></li>',
                        get_category_link( $catId ),
                        get_cat_name( $catId )
                    );
                }
                echo '</ul>';
            }
        }

    }

}