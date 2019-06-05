<?php
/**
 * WidgetCommentList.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\CommentList
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/CommentList/WidgetCommentList.html
 */

namespace ElebeeCore\Elementor\Widgets\CommentList;


use ElebeeCore\Elementor\Widgets\CommentList\Lib\Walker;
use ElebeeCore\Elementor\Widgets\WidgetBase;
use ElebeeCore\Lib\Elebee;
use ElebeeCore\Lib\Util\Template;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

\defined( 'ABSPATH' ) || exit;

/**
 * Class WidgetCommentList
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\CommentList
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/CommentList/WidgetCommentList.html
 */
class WidgetCommentList extends WidgetBase {

    /**
     * @since 0.1.0
     * @var string
     */
    protected $commentPaginationPageTemplate;

    /**
     * @since 0.1.0
     * @var mixed|void
     */
    protected $pageComments;

    /**
     * @since 0.1.0
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

    /**
     * @since 0.7.1
     * @var string
     */
    private $assetsPath;

    /**
     * CommentList constructor.
     *
     * @since 0.1.0
     *
     * @param array $data
     * @param array $args
     * @throws \Exception
     */
    public function __construct( array $data = [], array $args = null ) {

        parent::__construct( $data, $args );

        $this->assetsPath = get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Elementor/Widgets/CommentList/assets/';
        $this->commentPaginationPageTemplate = '<li>%s</li>';
        $this->pageComments = get_option( 'page_comments' );
    }

    /**
     * @since 0.7.1
     */
    public function enqueueStyles() {
        wp_enqueue_style( $this->get_name(), $this->assetsPath . 'css/comment-list.css', [], Elebee::VERSION, 'all' );
    }

    /**
     * @since 0.7.1
     */
    public function enqueueScripts() {

    }

    /**
     * Retrieve image widget name.
     *
     * @since 0.1.0
     */
    public function get_name() {

        return 'comments';

    }

    /**
     * Retrieve image widget title.
     *
     * @since 0.1.0
     */
    public function get_title() {

        return __( 'Comments', 'elebee' );

    }

    /**
     * Retrieve image widget icon.
     *
     * @since 0.1.0
     */
    public function get_icon() {

        return 'fa fa-comments-o';

    }

    public function get_keywords() {

        return [ 'comments', 'list', 'form' ];

    }

    /**
     * Retrieve the list of categories the image gallery widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since 0.1.0
     */
    public function get_categories() {

        return [ 'rto-elements' ];

    }

    /**
     * Register image widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 0.1.0
     */
    protected function _register_controls() {
        #<editor-fold desc="Elementor Tab Content">
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
                'options' => $this->getCommentPages(),
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
            'comment_list_author_structure',
            [
                'label' => __( 'Author Structure', 'elebee' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '%s <span class="says">says:</span>',
                'description' => __( '%s: Placeholder for author name.', 'elebee'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'comment_list_date_structure',
            [
                'label' => __( 'Date Structure', 'elebee' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Posted at %1$s %2$s ',
                'description' => __( '%1$s: Placeholder for date', 'elebee') . '<br>' . __( '%2$s: Placeholder for time', 'elebee'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'comment_date_format',
            [
                'label' => __( 'Date Format', 'elementor' ),
                'type' => Controls_Manager::SELECT2,
                'options' => [
                    'j-f-y' => 'j. F Y',
                    'y-m-d' => 'Y-m-d',
                    'm-d-y' => 'm/d/Y',
                    'd-m-y' => 'd/m/Y',
                    'mdy' => 'm.d.Y',
                    'custom' => __( 'Custom', 'elebee' ),
                ],
                'default' => 'j-f-y',
            ]
        );

        $this->add_control(
            'comment_date_format_custom',
            [
                'label' => __( 'Date Format Custom', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'comment_date_format' => 'custom',
                ],
                'description' => __( 'For a reference on how to format a date, visit the <a href="http://php.net/manual/en/function.date.php#refsect1-function.date-parameters" target="_blank">php date manual</a>.', 'elebee' ),
            ]
        );

        $this->add_control(
            'comment_time_format_custom',
            [
                'label' => __( 'Date Time Custom', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'H:i',
                'description' => __( 'For a reference on how to format a time, visit the <a href="http://php.net/manual/en/function.date.php#refsect1-function.date-parameters" target="_blank">php date manual</a>.', 'elebee' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'reply_format',
            [
                'label' => __( 'Reply Format', 'elebee' ),
            ]
        );

        $this->add_control(
            'comment_reply_show_avatar',
            [
                'label' => __( 'Show avatar', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'comment_reply_author_structure',
            [
                'label' => __( 'Author Structure', 'elebee' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '%s <span class="says">says:</span>',
                'description' => __( '%s: Placeholder for author name.', 'elebee'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'comment_reply_date_structure',
            [
                'label' => __( 'Date Structure', 'elebee' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Posted at %1$s %2$s ',
                'description' => __( '%1$s: Placeholder for date', 'elebee') . '<br>' . __( '%2$s: Placeholder for time', 'elebee'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'comment_reply_date_format',
            [
                'label' => __( 'Date Format', 'elementor' ),
                'type' => Controls_Manager::SELECT2,
                'options' => [
                    'j-f-y' => 'j. F Y',
                    'y-m-d' => 'Y-m-d',
                    'm-d-y' => 'm/d/Y',
                    'd-m-y' => 'd/m/Y',
                    'mdy' => 'm.d.Y',
                    'custom' => __( 'Custom', 'elebee' ),
                ],
                'default' => 'j-f-y',
            ]
        );

        $this->add_control(
            'comment_reply_date_format_custom',
            [
                'label' => __( 'Date Format Custom', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'comment_date_format' => 'custom',
                ],
                'description' => __( 'For a reference on how to format a date, visit the <a href="http://php.net/manual/en/function.date.php#refsect1-function.date-parameters" target="_blank">php date manual</a>.', 'elebee' ),
            ]
        );

        $this->add_control(
            'comment_reply_time_format_custom',
            [
                'label' => __( 'Date Time Custom', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'H:i',
                'description' => __( 'For a reference on how to format a time, visit the <a href="http://php.net/manual/en/function.date.php#refsect1-function.date-parameters" target="_blank">php date manual</a>.', 'elebee' ),
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
                    'placeholder' => get_option( 'comments_per_page' ),
                    'description' => sprintf( __( 'Default number for the "comments per page" option is set on <a href="%1$s" target="_blank">WP Settings > Discussion</a> page', 'elebee' ), admin_url( '/options-discussion.php#comments_per_page' ) ),
                    'min' => 1,
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_position',
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
                'comment_list_show_all',
                [
                    'label' => __( 'Show all pages', 'elebee' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'elebee' ),
                    'label_off' => __( 'No', 'elebee' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_end_size',
                [
                    'label' => __( 'Start/End size', 'elebee' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 2,
                    'placeholder' => 2,
                    'description' => __( 'How many numbers on either the start and the end list edges.', 'elebee' ),
                    'min' => 1,
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                        'comment_list_show_all!' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_mid_size',
                [
                    'label' => __( 'Middle size', 'elebee' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 2,
                    'placeholder' => 2,
                    'description' => __( 'How many numbers to either side of current page, but not including current page.', 'elebee' ),
                    'min' => 1,
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                        'comment_list_show_all!' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_first_last',
                [
                    'label' => __( 'First/last buttons?', 'elebee' ),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                    ],
                ]
            );

            $this->start_controls_tabs(
                'navigation_buttons'
            );

            $this->start_controls_tab(
                'navigation_button_prev',
                [
                    'label' => __( 'Previous', 'elebee' ),
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_prev_text',
                [
                    'label' => __( 'Label', 'elebee' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Previous', 'elebee' ),
                ]
            );

            $this->add_control(
                'comment_list_prev_icon',
                [
                    'label' => __( 'Icon', 'elebee' ),
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
                ]
            );

            $this->add_control(
                'comment_list_prev_icon_position',
                [
                    'label' => __( 'Icon Position', 'elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'left' => __( 'Before', 'elementor' ),
                        'right' => __( 'After', 'elementor' ),
                    ],
                    'default' => 'left',
                    'condition' => [
                        'comment_list_prev_icon!' => '',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_prev_icon_indent',
                [
                    'label' => __( 'Icon Spacing', 'elementor' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'condition' => [
                        'comment_list_prev_icon!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elebee-comments-prev-button .elebee-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elebee-comments-prev-button .elebee-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_prev_icon_view',
                [
                    'label' => __( 'View', 'elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'default' => __( 'Default', 'elementor' ),
                        'stacked' => __( 'Full', 'elebee' ),
                        'framed' => __( 'Framed', 'elementor' ),
                    ],
                    'default' => 'default',
                    'condition' => [
                        'comment_list_prev_icon!' => '',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'navigation_button_next',
                [
                    'label' => __( 'Next', 'elebee' ),
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_next_text',
                [
                    'label' => __( 'Label', 'elebee' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Next', 'elebee' ),
                ]
            );

            $this->add_control(
                'comment_list_next_icon',
                [
                    'label' => __( 'Icon', 'elebee' ),
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
                ]
            );

            $this->add_control(
                'comment_list_next_icon_position',
                [
                    'label' => __( 'Icon Position', 'elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left' => __( 'Before', 'elementor' ),
                        'right' => __( 'After', 'elementor' ),
                    ],
                    'condition' => [
                        'comment_list_next_icon!' => '',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_next_icon_indent',
                [
                    'label' => __( 'Icon Spacing', 'elementor' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'condition' => [
                        'comment_list_next_icon!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elebee-comments-next-button .elebee-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elebee-comments-next-button .elebee-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_next_icon_view',
                [
                    'label' => __( 'View', 'elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'default' => __( 'Default', 'elementor' ),
                        'stacked' => __( 'Full', 'elebee' ),
                        'framed' => __( 'Framed', 'elementor' ),
                    ],
                    'default' => 'default',
                    'condition' => [
                        'comment_list_next_icon!' => '',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'navigation_button_first',
                [
                    'label' => __( 'First', 'elebee' ),
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                        'comment_list_first_last' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_first_text',
                [
                    'label' => __( 'Label', 'elebee' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'First', 'elebee' ),
                ]
            );

            $this->add_control(
                'comment_list_first_icon',
                [
                    'label' => __( 'Icon', 'elebee' ),
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
                ]
            );

            $this->add_control(
                'comment_list_first_icon_position',
                [
                    'label' => __( 'Icon Position', 'elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left' => __( 'Before', 'elementor' ),
                        'right' => __( 'After', 'elementor' ),
                    ],
                    'condition' => [
                        'comment_list_first_icon!' => '',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_first_icon_indent',
                [
                    'label' => __( 'Icon Spacing', 'elementor' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'condition' => [
                        'comment_list_first_icon!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elebee-comments-first-button .elebee-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elebee-comments-first-button .elebee-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_first_icon_view',
                [
                    'label' => __( 'View', 'elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'default' => __( 'Default', 'elementor' ),
                        'stacked' => __( 'Full', 'elebee' ),
                        'framed' => __( 'Framed', 'elementor' ),
                    ],
                    'default' => 'default',
                    'condition' => [
                        'comment_list_first_icon!' => '',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'navigation_button_last',
                [
                    'label' => __( 'Last', 'elebee' ),
                    'condition' => [
                        'comment_list_paginate' => 'yes',
                        'comment_list_first_last' => 'yes',
                    ],
                ]
            );


            $this->add_control(
                'comment_list_last_text',
                [
                    'label' => __( 'Label', 'elebee' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Last', 'elebee' ),
                ]
            );

            $this->add_control(
                'comment_list_last_icon',
                [
                    'label' => __( 'Icon', 'elebee' ),
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
                ]
            );

            $this->add_control(
                'comment_list_last_icon_position',
                [
                    'label' => __( 'Icon Position', 'elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left' => __( 'Before', 'elementor' ),
                        'right' => __( 'After', 'elementor' ),
                    ],
                    'condition' => [
                        'comment_list_last_icon!' => '',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_last_icon_indent',
                [
                    'label' => __( 'Icon Spacing', 'elementor' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'condition' => [
                        'comment_list_last_icon!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elebee-comments-last-button .elebee-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elebee-comments-last-button .elebee-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'comment_list_last_icon_view',
                [
                    'label' => __( 'View', 'elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'default' => __( 'Default', 'elementor' ),
                        'stacked' => __( 'Full', 'elebee' ),
                        'framed' => __( 'Framed', 'elementor' ),
                    ],
                    'default' => 'default',
                    'condition' => [
                        'comment_list_last_icon!' => '',
                    ],
                ]
            );
        }

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
        #</editor-fold>

        #<editor-fold desc="Elementor Tab Style">
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
                'label' => __( 'Text Color', 'elebee' ),
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

        $this->add_responsive_control(
            'text_align',
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
                'selectors' => [
                    '{{WRAPPER}} .comment-body' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
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

        $this->add_responsive_control(
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .pagination',
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
                    'value' => Scheme_Color::COLOR_3,
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
                    '{{WRAPPER}} .page-numbers, {{WRAPPER}} .elebee-button-icon' => 'background-color: {{VALUE}};',
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
                    'value' => Scheme_Color::COLOR_3,
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
                        'step' => 100,
                    ],
                ],
                'default' => [
                    'size' => 600,
                    'unit' => 'ms',
                ],
                'selectors' => [
                    '{{WRAPPER}} .page-numbers:not(.current)' => 'transition: color {{SIZE}}{{UNIT}}, background-color {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => __( 'Icon', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'icon_colors' );

        $this->start_controls_tab(
            'icon_colors_normal',
            [
                'label' => __( 'Normal', 'elementor' ),
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => __( 'Primary Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elebee-view-stacked .elebee-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elebee-view-framed .elebee-icon, {{WRAPPER}} .elebee-view-default .elebee-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => __( 'Secondary Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elebee-view-framed .elebee-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elebee-view-stacked .elebee-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_colors_hover',
            [
                'label' => __( 'Hover', 'elementor' ),
            ]
        );

        $this->add_control(
            'hover_primary_color',
            [
                'label' => __( 'Primary Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elebee-view-stacked:hover .elebee-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elebee-view-framed:hover .elebee-icon, {{WRAPPER}}.elebee-view-default:hover .elebee-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_secondary_color',
            [
                'label' => __( 'Secondary Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elebee-view-framed:hover .elebee-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elebee-view-stacked:hover .elebee-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_transition_duration',
            [
                'label' => __( 'Transition Duration', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'ms' ],
                'range' => [
                    'ms' => [
                        'min' => 100,
                        'max' => 2000,
                        'step' => 100,
                    ],
                ],
                'default' => [
                    'size' => 600,
                    'unit' => 'ms',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elebee-button-icon' => 'transition: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'icons_size',
            [
                'label' => __( 'Size', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .elebee-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_padding',
            [
                'label' => __( 'Padding', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elebee-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
            ]
        );

        $this->add_control(
            'rotate',
            [
                'label' => __( 'Rotate', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elebee-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_control(
            'border_width',
            [
                'label' => __( 'Border Width', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .elebee-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elebee-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        #</editor-fold>
    }

    /**
     * @since 0.1.0
     *
     * @return string
     */
    protected function getCommentPagination(): string {

        $settings = $this->get_settings_for_display();

        $prevRenderer = new Template( __DIR__ . '/partials/page-button.php', $this->getButtonArgs( 'prev' ) );
        $nextRenderer = new Template( __DIR__ . '/partials/page-button.php', $this->getButtonArgs( 'next' ) );

        $pagination = paginate_comments_links( [
            'echo' => false,
            'show_all' => $settings[ 'comment_list_show_all' ] === 'yes' ? true : false,
            'mid_size' => $settings[ 'comment_list_mid_size' ],
            'end_size' => $settings[ 'comment_list_end_size' ],
            'type' => 'array',
            'add_fragment' => '',
            'base' => $this->paginationSettings[ 'pagenumLink' ],
            'current' => $this->paginationSettings[ 'currentPage' ],
            'format' => $this->paginationSettings[ 'urlFormat' ],
            'total' => $this->paginationSettings[ 'totalPages' ],
            'prev_text' => $prevRenderer->getRendered(),
            'next_text' => $nextRenderer->getRendered(),
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
                } elseif ( $count > 0 ) {
                    $paginationNext = $pageData;
                } else {
                    $paginationPrev = $pageData;
                }

                ++$count;
            }

            if ( 'yes' === $settings['comment_list_first_last'] ) {

                $firstLink = str_replace( '%_%', '', $this->paginationSettings[ 'pagenumLink' ] );
                $firstLink = str_replace( '%#%', '', $firstLink );
                if ( $this->paginationSettings[ 'addArgs' ] ) {
                    $firstLink = add_query_arg( $this->paginationSettings[ 'addArgs' ], $firstLink );
                }
                $firstArgs = $this->getButtonArgs( 'first' );
                $firstArgs[ 'url' ] = $firstLink;
                $firstRenderer = new Template( __DIR__ . '/partials/end-li.php', $firstArgs );


                $lastLink = str_replace( '%_%', $this->paginationSettings[ 'urlFormat' ], $this->paginationSettings[ 'pagenumLink' ] );
                $lastLink = str_replace( '%#%', $this->paginationSettings[ 'totalPages' ], $lastLink );
                if ( $this->paginationSettings[ 'addArgs' ] ) {
                    $lastLink = add_query_arg( $this->paginationSettings[ 'addArgs' ], $lastLink );
                }
                $lastArgs = $this->getButtonArgs( 'last' );
                $lastArgs[ 'url' ] = $lastLink;
                $lastRenderer = new Template( __DIR__ . '/partials/end-li.php', $lastArgs );

                if ( $this->paginationSettings[ 'currentPage' ] > 1 ) {
                    $paginationFirst = $firstRenderer->getRendered();
                }
                if ( $this->paginationSettings[ 'currentPage' ] < $this->paginationSettings[ 'totalPages' ] ) {
                    $paginationLast = $lastRenderer->getRendered();
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

    protected function getButtonArgs( $button ) {
        $view = $this->getSetting( 'comment_list_' .$button . '_icon_view' );
        $viewClass =  !empty( $view ) ? ' elebee-view-' . $view : '';

        $args = [
            'text' => $this->getSetting( 'comment_list_' . $button . '_text' ),
            'buttonClass' => 'elebee-comments-' . $button . '-button' . $viewClass,
            'iconClass' => esc_attr( $this->getSetting( 'comment_list_' . $button . '_icon' ) ),
            'iconAlign' => esc_attr( $this->getSetting( 'comment_list_' . $button . '_icon_position' ) ),
        ];

        return $args;
    }

    /**
     * @param $name string
     * @return string
     */
    protected function getSetting( $name ) {
        $settings = $this->get_settings_for_display();

        return isset( $settings[ $name ] ) ? $settings[ $name ] : '';
    }

    /**
     * @since 0.1.0
     *
     * @return array
     */
    protected function getCommentPages(): array {

        global $wpdb;

        $options = [];

        $commentPosts = $wpdb->get_results( "
          SELECT ID, post_title 
          FROM $wpdb->posts 
          WHERE comment_status = 'open' 
          AND post_status = 'publish'
          ", ARRAY_A );

        foreach ( $commentPosts as $commentPost ) {

            $options[$commentPost['ID']] = apply_filters( 'the_title', $commentPost['post_title'] );

            if ( (int)$commentPost['ID'] === get_the_ID() ) {
                $options[$commentPost['ID']] .= __( ' (Current page)', 'elebee' );
            }

        }

        return $options;

    }

    /**
     * @since 0.1.0
     *
     * @param array $comments
     *
     * @return void
     */
    protected function setPaginationSettings( array $comments ) {

        if ( !get_option( 'page_comments' ) ) {
            return;
        }

        $format_args = [];
        $url_query_args = [];
        $settings = $this->get_settings_for_display();
        $commentsPerPage = !empty( $settings[ 'comment_list_per_page' ] ) ? $settings[ 'comment_list_per_page' ] : get_option( 'comments_per_page' );

        $this->paginationSettings[ 'totalPages' ] = get_comment_pages_count( $comments, $commentsPerPage );

        # Setting up default values based on the current URL.
        $this->paginationSettings[ 'pagenumLink' ] = html_entity_decode( get_pagenum_link() );
        $url_parts = explode( '?', $this->paginationSettings[ 'pagenumLink' ] );

        # Append the format placeholder to the base URL.
        $this->paginationSettings[ 'pagenumLink' ] = trailingslashit( $url_parts[0] ) . '%_%';

        $this->paginationSettings[ 'pageVar' ] = 'paged-' . $this->get_id();
        $this->paginationSettings[ 'currentPage' ] = filter_input( INPUT_GET, $this->paginationSettings[ 'pageVar' ], FILTER_VALIDATE_INT );
        if ( empty( $this->paginationSettings[ 'currentPage' ] ) ) {
            $this->paginationSettings[ 'currentPage' ] = 1;
        }

        $this->paginationSettings[ 'urlFormat' ] = '?' . $this->paginationSettings[ 'pageVar' ] . '=%#%';

        # Merge additional query vars found in the original URL into 'add_args' array.
        if ( isset( $url_parts[1] ) ) {
            # Find the format argument.
            $format = explode( '?', str_replace( '%_%', $this->paginationSettings[ 'urlFormat' ], $this->paginationSettings[ 'pagenumLink' ] ) );
            $format_query = isset( $format[1] ) ? $format[1] : '';
            wp_parse_str( $format_query, $format_args );

            # Find the query args of the requested URL.
            wp_parse_str( $url_parts[1], $url_query_args );

            # Remove the format argument from the array of query arguments, to avoid overwriting custom format.
            foreach ( $format_args as $format_arg => $format_arg_value ) {
                unset( $url_query_args[$format_arg] );
            }

            $this->paginationSettings[ 'addArgs' ] = urlencode_deep( $url_query_args );
        }

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

        $settings = $this->get_settings_for_display();
        $args = [
            'post_id' => $settings[ 'comments_from_post' ],
            'status' => 'approve',
        ];

        $comments = get_comments( $args );
        if ( empty( $comments ) ) {
            $noCommentsTitle = '<span class="elebee-notice-title">' . __( 'No comments available!', 'elebee' ) . '</span>';
            echo '<div class="elebee-notice elebee-notice-warning">' . $noCommentsTitle . '</div>';

            return;
        }

        $this->setPaginationSettings( $comments );

        $allowPagination = $this->pageComments && 'yes' === $settings['comment_list_paginate'];
        $pagination = '';
        if ( $allowPagination ) {
            $pagination = $this->getCommentPagination();
        }

        if ( $allowPagination && in_array( $settings['comment_list_position'], [ 'top-bottom', 'top' ] ) ) {
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
        if ( $allowPagination && in_array( $settings['comment_list_position'], [ 'top-bottom', 'bottom' ] ) ) {
            echo $pagination;
        }

    }

}
