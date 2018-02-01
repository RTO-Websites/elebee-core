<?php

namespace ElebeeCore\Widgets\General\CommentList;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use ElebeeCore\Lib\Template;
use ElebeeCore\Widgets\CommentList\Lib\Walker;

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Image Widget
 */
class CommentList extends Widget_Base {
    /**
     * @var string
     *
     * @since 1.1.0
     * @ignore
     */
    protected $commentPaginationPageTemplate;

    /**
     * @var mixed|void
     */
    protected $pageComments;

    /**
     * @var array
     */
    protected $paginationSettings = [
        'pageVar' => '',
        'currentPage' => '',
        'pagenumLink' => '',
        'urlFormat' => '',
        'addArgs' => '',
        'totalPages' => '',
    ];

    public function __construct( array $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $this->commentPaginationPageTemplate = '<li>%s</li>';
        $this->pageComments = get_option( 'page_comments' );
    }

    /**
     * Retrieve image widget name.
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'comments';
    }

    /**
     * Retrieve image widget title.
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Comments', 'elebee' );
    }

    /**
     * Retrieve image widget icon.
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'fa fa-comments-o';
    }

    /**
     * Retrieve the list of categories the image gallery widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'rto-elements' ];
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
            'section_comments',
            [
                'label' => __( 'Comments', 'elebee' ),
            ]
        );

        $this->add_control(
            'comments_from_post',
            [
                'label' => __( 'Post', 'elebee' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'default' => get_the_ID(),
                'options' => $this->getAllCommentPages(),
            ]
        );

        $this->add_responsive_control(
            'comment_align',
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
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'comment_list_allow_reply',
            [
                'label' => __( 'Allow replies', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'comment_show_avatar',
            [
                'label' => __( 'Show avatar', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'comment_avatar_size',
            [
                'label' => __( 'Avatar size', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 32,
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'comment_show_avatar' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .comment-author .avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'comment_list_author_format',
            [
                'label' => __( 'Author format', 'elebee' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '%s <span class="says">says:</span>',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'comment_list_date_format',
            [
                'label' => __( 'Comment format<br><small>For a reference on how to format a date, visit the <a href="http://php.net/manual/en/function.date.php#refsect1-function.date-parameters" target="_blank">php date manual</a>.</small>', 'elebee' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'd.m.Y \u\m H:i',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_pagination',
            [
                'label' => __( 'Pagination', 'elebee' ),
            ]
        );

        if ( !get_option( 'page_comments' ) ) {
            $this->add_control(
                'html_msg',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => sprintf( __( 'Please note: pagination is only available once the option <a href="%1$s" target="_blank">Break comments into pages</a> has been activated.', 'elebee' ), admin_url( '/options-discussion.php#page_comments' ) ),
                    'content_classes' => 'your-class',
                ]
            );
        } else {
            $this->add_control(
                'comment_list_paginate',
                [
                    'label' => __( 'Pagination', 'elebee' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'comment_list_per_page',
                [
                    'label' => __( 'Comments per page', 'elebee' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => get_option( 'comments_per_page' ),
                    'min' => 1,
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_pagination_position',
                [
                    'label' => __( 'Pagination position', 'elebee' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'top-bottom',
                    'options' => [
                        'top-bottom' => __( 'Top and bottom', 'elebee' ),
                        'top' => __( 'Top only', 'elebee' ),
                        'bottom' => __( 'Bottom only', 'elebee' ),
                    ],
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_pagination_prev_label',
                [
                    'label' => __( 'Previous label', 'elebee' ),
                    'type' => Controls_Manager::ICON,
                    'default' => 'fa fa-angle-left',
                    'include' => [
                        'fa fa-angle-double-left',
                        'fa fa-angle-left',
                        'fa fa-arrow-circle-left',
                        'fa fa-arrow-circle-o-left',
                        'fa fa-arrow-left',
                        'fa fa-caret-left',
                        'fa fa-chevron-circle-left',
                        'fa fa-chevron-left',
                        'fa fa-long-arrow-left',
                        'fa fa-toggle-left',
                    ],
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_pagination_next_label',
                [
                    'label' => __( 'Next label', 'elebee' ),
                    'type' => Controls_Manager::ICON,
                    'default' => 'fa fa-angle-right',
                    'include' => [
                        'fa fa-angle-right',
                        'fa fa-angle-double-right',
                        'fa fa-arrow-circle-right',
                        'fa fa-arrow-circle-o-right',
                        'fa fa-arrow-right',
                        'fa fa-caret-right',
                        'fa fa-chevron-circle-right',
                        'fa fa-chevron-right',
                        'fa fa-long-arrow-right',
                        'fa fa-toggle-right',
                    ],
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_pagination_first_last',
                [
                    'label' => __( 'First/last buttons?', 'elebee' ),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );

            $this->add_control(
                'comment_list_pagination_first_button',
                [
                    'label' => __( 'First label', 'elebee' ),
                    'type' => Controls_Manager::ICON,
                    'default' => 'fa fa-angle-double-left',
                    'include' => [
                        'fa fa-angle-double-left',
                        'fa fa-angle-left',
                        'fa fa-arrow-circle-left',
                        'fa fa-arrow-circle-o-left',
                        'fa fa-arrow-left',
                        'fa fa-caret-left',
                        'fa fa-chevron-circle-left',
                        'fa fa-chevron-left',
                        'fa fa-long-arrow-left',
                        'fa fa-toggle-left',
                    ],
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                        'comment_list_pagination_first_last' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_pagination_last_button',
                [
                    'label' => __( 'Last label', 'elebee' ),
                    'type' => Controls_Manager::ICON,
                    'default' => 'fa fa-angle-double-right',
                    'include' => [
                        'fa fa-angle-double-right',
                        'fa fa-angle-right',
                        'fa fa-arrow-circle-right',
                        'fa fa-arrow-circle-o-right',
                        'fa fa-arrow-right',
                        'fa fa-caret-right',
                        'fa fa-chevron-circle-right',
                        'fa fa-chevron-right',
                        'fa fa-long-arrow-right',
                        'fa fa-toggle-right',
                    ],
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                        'comment_list_pagination_first_last' => 'yes',
                    ],
                ]
            );
        }

        $this->end_controls_section();

        $this->start_controls_section(
            'section_comments_style',
            [
                'label' => __( 'Comments', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'comment_color',
            [
                'label' => __( 'Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .comment-body' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'comment_margin',
            [
                'label' => __( 'Margin', 'elebee' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .comment-list li' => 'margin: 0 {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .comment-list li:not(:first-child)' => 'margin-top: {{TOP}}{{UNIT}};',
                    '{{WRAPPER}} .comment-list li:not(:last-child)' => 'margin-bottom: {{BOTTOM}}{{UNIT}};',
                ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 15,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
            ]
        );

        $this->add_control(
            'comment_padding',
            [
                'label' => __( 'Padding', 'elebee' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .comment-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'condition' => [
                    'comment_list_paginate' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'comment_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .comment-list',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_pagination_style',
            [
                'label' => __( 'Pagination', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'pagination_padding',
            [
                'label' => __( 'Padding', 'elebee' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .pagination li > span, {{WRAPPER}} .pagination li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_control(
            'pagination_spacing',
            [
                'label' => __( 'Spacing', 'elebee' ),
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
                    '{{WRAPPER}} .pagination:first-child' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pagination:last-child' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'elebee' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Text Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3
                ],
                'selectors' => [
                    '{{WRAPPER}} .page-numbers' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => __( 'Background Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .page-numbers' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'elebee' ),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3
                ],
                'selectors' => [
                    '{{WRAPPER}} .page-numbers:not(.current):hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __( 'Background Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .page-numbers:not(.current):hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_transition_duration',
            [
                'label' => __( 'Button Transition Duration', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'ms' ],
                'range' => [
                    'ms' => [
                        'min' => 100,
                        'max' => 2000,
                    ],
                ],
                'default' => [
                    'size' => 600,
                    'unit' => 'ms',
                ],
                'selectors' => [
                    '{{WRAPPER}} .page-numbers:not(.current)' => 'transition: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .pagination',
            ]
        );

        $this->end_controls_section();
    }

    protected function getFirstButton( Template $renderer ): string {
        $settings = $this->get_settings();
        $link = str_replace( '%_%', '', $this->paginationSettings['pagenumLink'] );
        $link = str_replace( '%#%', '', $link );
        if ( $this->paginationSettings['addArgs'] ) {
            $link = add_query_arg( $this->paginationSettings['addArgs'], $link );
        }
        $renderer->setVar( 'url', esc_url( apply_filters( 'paginate_links', $link ) ) );
        $renderer->setVar( 'iconClass', $settings['comment_list_pagination_first_button'] );
        return $renderer->getRendered();
    }

    protected function getLastButton( Template $renderer ): string {
        $settings = $this->get_settings();
        $link = str_replace( '%_%', $this->paginationSettings['urlFormat'], $this->paginationSettings['pagenumLink'] );
        $link = str_replace( '%#%', $this->paginationSettings['totalPages'], $link );
        if ( $this->paginationSettings['addArgs'] ) {
            $link = add_query_arg( $this->paginationSettings['addArgs'], $link );
        }
        $renderer->setVar( 'url', esc_url( apply_filters( 'paginate_links', $link ) ) );
        $renderer->setVar( 'iconClass', $settings['comment_list_pagination_last_button'] );
        return $renderer->getRendered();
    }

    protected function getCommentPagination(): string {
        $settings = $this->get_settings();

        $pagination = paginate_comments_links( [
            'echo' => false,
            'mid_size' => 2,
            'end_size' => 3,
            'type' => 'array',
            'add_fragment' => '',
            'base' => $this->paginationSettings['pagenumLink'],
            'current' => $this->paginationSettings['currentPage'],
            'format' => $this->paginationSettings['urlFormat'],
            'total' => $this->paginationSettings['totalPages'],
            'next_text' => '<i class="' . esc_attr( $settings['comment_list_pagination_next_label'] ) . '"></i>',
            'prev_text' => '<i class="' . esc_attr( $settings['comment_list_pagination_prev_label'] ) . '"></i>',
        ] );

        $paginationPages = '';
        $paginationPrev = '';
        $paginationNext = '';
        $paginationFirst = '';
        $paginationLast = '';

        if ( !empty( $pagination ) ) {
            $count = 0;

            foreach ( $pagination as $pageData ) {
                if ( is_numeric( strip_tags( $pageData ) )
                    || strip_tags( $pageData ) === '&hellip;'
                ) {
                    $paginationPages .= sprintf( $this->commentPaginationPageTemplate, $pageData );
                } else {
                    if ( $count > 0 ) {
                        $paginationNext = $pageData;
                    } else {
                        $paginationPrev = $pageData;
                    }
                }

                ++$count;
            }

            if ( 'yes' === $settings['comment_list_pagination_first_last'] ) {
                $endRenderer = new Template( __DIR__ . '/partials/end-li.php' );
                if ( $this->paginationSettings['currentPage'] > 1 ) {
                    $paginationFirst = $this->getFirstButton( $endRenderer );
                }
                if ( $this->paginationSettings['currentPage'] < $this->paginationSettings['totalPages'] ) {
                    $paginationLast = $this->getLastButton( $endRenderer );
                }
            }
        }

        $template = new Template( __DIR__ . '/partials/comment-pagination.php', [
            'paginationFirst' => $paginationFirst,
            'paginationPrev' => $paginationPrev,
            'paginationPages' => $paginationPages,
            'paginationNext' => $paginationNext,
            'paginationLast' => $paginationLast,
        ] );
        return $template->getRendered();
    }

    protected function getAllCommentPages(): array {
        global $wpdb;

        $options = [];
        $curID = get_the_ID();
        $commentPosts = $wpdb->get_results( "
          SELECT ID, post_title 
          FROM $wpdb->posts 
          WHERE comment_status = 'open' 
          AND post_status = 'publish'
          ", ARRAY_A );
        foreach ( $commentPosts as $commentPost ) {
            $name = apply_filters( 'the_title', $commentPost['post_title'] );
            if ( (int)$commentPost['ID'] === $curID ) {
                $name = __( 'Aktuelle Seite', 'elebee' );
            }
            $options[$commentPost['ID']] = $name;
        }
        return $options;
    }

    protected function setPaginationSettings( array $comments ) {
        $settings = $this->get_settings();
        $this->paginationSettings['totalPages'] = get_comment_pages_count( $comments, $settings['comment_list_per_page'] );

        // Setting up default values based on the current URL.
        $this->paginationSettings['pagenumLink'] = html_entity_decode( get_pagenum_link() );
        $url_parts = explode( '?', $this->paginationSettings['pagenumLink'] );

        // Append the format placeholder to the base URL.
        $this->paginationSettings['pagenumLink'] = trailingslashit( $url_parts[0] ) . '%_%';

        $this->paginationSettings['pageVar'] = 'paged-' . $this->get_id();
        $this->paginationSettings['currentPage'] = filter_input( INPUT_GET, $this->paginationSettings['pageVar'], FILTER_VALIDATE_INT );
        if ( empty( $this->paginationSettings['currentPage'] ) ) {
            $this->paginationSettings['currentPage'] = 1;
        }

        $this->paginationSettings['urlFormat'] = '?' . $this->paginationSettings['pageVar'] . '=%#%';

        // Merge additional query vars found in the original URL into 'add_args' array.
        if ( isset( $url_parts[1] ) ) {
            // Find the format argument.
            $format = explode( '?', str_replace( '%_%', $this->paginationSettings['urlFormat'], $this->paginationSettings['pagenumLink'] ) );
            $format_query = isset( $format[1] ) ? $format[1] : '';
            wp_parse_str( $format_query, $format_args );

            // Find the query args of the requested URL.
            wp_parse_str( $url_parts[1], $url_query_args );

            // Remove the format argument from the array of query arguments, to avoid overwriting custom format.
            foreach ( $format_args as $format_arg => $format_arg_value ) {
                unset( $url_query_args[$format_arg] );
            }

            $this->paginationSettings['addArgs'] = urlencode_deep( $url_query_args );
        }
    }

    /**
     * Render image widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings();
        $args = [
            'post_id' => $settings['comments_from_post'],
        ];
        $comments = get_comments( $args );
        $this->setPaginationSettings( $comments );

        $allowPagination = $this->pageComments && 'yes' === $settings['comment_list_paginate'];
        $pagination = '';
        if ( $allowPagination ) {
            $pagination = $this->getCommentPagination();
        }

        if ( $allowPagination && in_array( $settings['comment_list_pagination_position'], [ 'top-bottom', 'top' ] ) ) {
            echo $pagination;
        }
        echo '<ul class="comment-list">';
        wp_list_comments(
            [
                'per_page' => ( $allowPagination ? $settings['comment_list_per_page'] : '' ),
                'avatar_size' => ( 'yes' === $settings['comment_show_avatar'] ? $settings['comment_avatar_size']['size'] : 0 ),
                'walker' => new Walker( $settings ),
                'page' => $this->paginationSettings['currentPage'],
            ],
            $comments
        );
        echo '</ul>';
        if ( $allowPagination && in_array( $settings['comment_list_pagination_position'], [ 'top-bottom', 'bottom' ] ) ) {
            echo $pagination;
        }
    }
}
