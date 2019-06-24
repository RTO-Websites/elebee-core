<?php
/**
 * WidgetCommentForm.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\CommentForm
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-extras.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/CommentForm/WidgetCommentForm.html
 */

namespace ElebeeCore\Elementor\Widgets\CommentForm;


use ElebeeCore\Elementor\Widgets\WidgetBase;
use ElebeeCore\Lib\Elebee;
use ElebeeCore\Lib\Util\Template;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

\defined( 'ABSPATH' ) || exit;

if ( !defined( '__COMMENTFORM__' ) ) {
    define( '__COMMENTFORM__', plugins_url() . '/elementor-rto/extensions/CommentForm' );
}

/**
 * Class WidgetCommentForm
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\CommentForm
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-extras.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/CommentForm/WidgetCommentForm.html
 */
class WidgetCommentForm extends WidgetBase {
    private $assetsPath = '';

    public $settings = null;

    private static $scriptEnqueued = false;

    public function __construct( array $data = [], array $args = null ) {
        parent::__construct( $data, $args );

        $this->assetsPath = get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Elementor/Widgets/CommentForm/assets/';

    }

    /**
     * Implement public js and css files.
     *
     * @return void
     */
    public function definePublicHooks() {

        parent::definePublicHooks();

        $this->getLoader()->addAction( 'wp_ajax_nopriv_ajaxcomments', $this, 'ajaxCommentSubmit' );

        $this->getLoader()->addFilter( 'comment_form_fields', $this, 'rearrangeFields', 100, 1  );

    }

    /**
     * Implement admin js and css files.
     *
     * @return void
     */
    public function defineAdminHooks() {

        parent::defineAdminHooks();

        $this->getLoader()->addFilter( 'elementor/widget/print_template', $this, 'skinPrintTemplate', 10, 2  );

    }

    /**
     * @since 0.1.0
     */
    public function enqueueStyles() {
        wp_enqueue_style( $this->get_name(), $this->assetsPath . 'css/comment-form.css', [], Elebee::VERSION, 'all' );
    }

    /**
     * @since 0.1.0
     */
    public function enqueueScripts() {
        wp_enqueue_script( $this->get_name(), $this->assetsPath . 'js/ajax-comments.js', [ 'jquery' ], Elebee::VERSION );

        // Prevent loading localized string multiple times.
        $wpScripts = wp_scripts();
        if ( ! $wpScripts->get_data( $this->get_name(), 'data' ) ) {
            // Localize the script with new data
            $translationArray = array(
                'fieldIsEmpty' => __( 'Field is empty', 'elebee' ),
                'emailInvalid' => __( 'Email format is invalid', 'elebee' ),
                'required' => __( 'This field is required', 'elebee'),
                'formSubmitSuccess' => __( 'Thank you for your submission!', 'elebee'),
            );

            wp_localize_script( $this->get_name(), 'themeLocalization', $translationArray );
        }
    }

    /**
     * Retrieve image widget name.
     *
     * @since 0.1.0
     */
    public function get_name(): string {

        return 'comment_form';

    }

    /**
     * Retrieve image widget title.
     *
     * @since 0.1.0
     */
    public function get_title(): string {

        return __( 'Comment Form', 'elebee' );

    }

    /**
     * Retrieve image widget icon.
     *
     * @since 0.1.0
     */
    public function get_icon(): string {

        return 'fa fa-wpforms';

    }

    public function get_keywords() {

        return [ 'guest book', 'comment form', 'user', 'form' ];

    }

    /**
     * Register image widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 0.1.0
     *
     * @return void
     */
    protected function _register_controls() {

        //<editor-fold desc="Elementor Tab Content">
        $this->start_controls_section(
            'section_comment_form',
            [
                'label' => __( 'Comment Form', 'elebee' ),
            ]
        );

        $this->add_control(
            'page',
            [
                'label' => __( 'Page', 'elebee' ),
                'description' => __( 'Comments get posted to the selected page.', 'elebee' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'default' => get_the_ID(),
                'options' => $this->getCommentPages(),
            ]
        );

        $this->add_control(
            'open_comments_pages',
            [
                'label' => __( 'Display Warning', 'elebee' ),
                'type' => Controls_Manager::HIDDEN,
                'default' => json_encode( $this->getCommentPages() ),
            ]
        );

        $this->start_controls_tabs(
            'fields_tabs'
        );

        $this->start_controls_tab(
            'name_tab',
            [
                'label' => __( 'Name', 'elebee' ),
            ]
        );

        $this->add_control(
            'show_name',
            [
                'label' => __( 'Visibility', 'elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor' ),
                'label_off' => __( 'Hide', 'elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'label_name',
            [
                'label' => __( 'Label', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Name', 'elebee' ),
                'condition' => [
                    'show_name' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'placeholder_name',
            [
                'label' => __( 'Placeholder', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Name', 'elebee' ),
                'condition' => [
                    'show_name' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'field_width_name',
            [
                'label' => __( 'Field Width', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Default', 'elementor' ),
                    '100' => '100%',
                    '80' => '80%',
                    '75' => '75%',
                    '66' => '66%',
                    '60' => '60%',
                    '50' => '50%',
                    '40' => '40%',
                    '33' => '33%',
                    '25' => '25%',
                    '20' => '20%',
                ],
                'default' => '100',
            ]
        );

        $this->add_control(
            'require_name',
            [
                'label' => __( 'Required', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'elementor' ),
                'label_off' => __( 'No', 'elementor' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'show_name' => 'yes',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'email_tab',
            [
                'label' => __( 'Email', 'elebee' )
            ]
        );

        $this->add_control(
            'show_email',
            [
                'label' => __( 'Visibility', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor' ),
                'label_off' => __( 'Hide', 'elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'label_email',
            [
                'label' => __( 'Label', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Email', 'elebee' ),
                'condition' => [
                    'show_email' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'placeholder_email',
            [
                'label' => __( 'Placeholder', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Email', 'elebee' ),
                'condition' => [
                    'show_email' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'field_width_email',
            [
                'label' => __( 'Field Width', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Default', 'elementor' ),
                    '100' => '100%',
                    '80' => '80%',
                    '75' => '75%',
                    '66' => '66%',
                    '60' => '60%',
                    '50' => '50%',
                    '40' => '40%',
                    '33' => '33%',
                    '25' => '25%',
                    '20' => '20%',
                ],
                'default' => '100',
            ]
        );

        $this->add_control(
            'require_email',
            [
                'label' => __( 'Required', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'elementor' ),
                'label_off' => __( 'No', 'elementor' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'show_email' => 'yes',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'extra_tab',
            [
                'label' => __( 'Extra', 'elebee' )
            ]
        );

        $this->add_control(
            'show_extra',
            [
                'label' => __( 'Visibility', 'elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor' ),
                'label_off' => __( 'Hide', 'elementor' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'use_extra_as_subject',
            [
                'label' => __( 'Field type', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'String', 'elementor' ),
                'label_off' => __( 'Url', 'elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'show_extra' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'label_extra',
            [
                'label' => __( 'Label', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Subject', 'elebee' ),
                'condition' => [
                    'show_extra' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'placeholder_extra',
            [
                'label' => __( 'Placeholder', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Subject', 'elebee' ),
                'condition' => [
                    'show_extra' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'field_width_extra',
            [
                'label' => __( 'Field Width', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Default', 'elementor' ),
                    '100' => '100%',
                    '80' => '80%',
                    '75' => '75%',
                    '66' => '66%',
                    '60' => '60%',
                    '50' => '50%',
                    '40' => '40%',
                    '33' => '33%',
                    '25' => '25%',
                    '20' => '20%',
                ],
                'default' => '100',
            ]
        );

        $this->add_control(
            'require_extra',
            [
                'label' => __( 'Required', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'elementor' ),
                'label_off' => __( 'No', 'elementor' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'show_extra' => 'yes',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'comment_tab',
            [
                'label' => __( 'Comment', 'elebee' )
            ]
        );

        $this->add_control(
            'label_comment',
            [
                'label' => __( 'Comment label', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Comment', 'elebee' ),
                'placeholder' => __( 'Type your comment label text here', 'elebee' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'placeholder_comment',
            [
                'label' => __( 'Placeholder', 'elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Comment', 'elebee' ),
                'placeholder' => __( 'Type your comment placeholder text here', 'elebee' ),
            ]
        );

        $this->add_responsive_control(
            'field_width_comment',
            [
                'label' => __( 'Comment Field Width', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Default', 'elementor' ),
                    '100' => '100%',
                    '80' => '80%',
                    '75' => '75%',
                    '66' => '66%',
                    '60' => '60%',
                    '50' => '50%',
                    '40' => '40%',
                    '33' => '33%',
                    '25' => '25%',
                    '20' => '20%',
                ],
                'default' => '100',
            ]
        );
        $this->add_control(
            'rows_comment',
            [
                'label' => __( 'Rows', 'elebee' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 4,
                'separator' => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'input_size',
            [
                'label' => __( 'Input Size', 'elebee' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'xs' => __( 'Extra Small', 'elementor' ),
                    'sm' => __( 'Small', 'elementor' ),
                    'md' => __( 'Medium', 'elementor' ),
                    'lg' => __( 'Large', 'elementor' ),
                    'xl' => __( 'Extra Large', 'elementor' ),
                ],
                'default' => 'sm',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_cookies_opt_in',
            [
                'label' => __( 'Show comments cookies opt-in checkbox.', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor' ),
                'label_off' => __( 'Hide', 'elementor' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'show_gdpr_opt_in',
            [
                'label' => __( 'Show GDPR opt-in checkbox.', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor' ),
                'label_off' => __( 'Hide', 'elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_submit_button',
            [
                'label' => __( 'Submit Button', 'elebee' ),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Text', 'elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Send', 'elementor' ),
                'placeholder' => __( 'Send', 'elementor' ),
            ]
        );

        $this->add_control(
            'button_size',
            [
                'label' => __( 'Size', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'xs' => __( 'Extra Small', 'elementor' ),
                    'sm' => __( 'Small', 'elementor' ),
                    'md' => __( 'Medium', 'elementor' ),
                    'lg' => __( 'Large', 'elementor' ),
                    'xl' => __( 'Extra Large', 'elementor' ),
                ],
                'default' => 'sm',
            ]
        );

        $this->add_responsive_control(
            'button_width',
            [
                'label' => __( 'Column Width', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Default', 'elementor' ),
                    '100' => '100%',
                    '80' => '80%',
                    '75' => '75%',
                    '66' => '66%',
                    '60' => '60%',
                    '50' => '50%',
                    '40' => '40%',
                    '33' => '33%',
                    '25' => '25%',
                    '20' => '20%',
                ],
                'default' => '100',
            ]
        );

        $this->add_responsive_control(
            'button_align',
            [
                'label' => __( 'Alignment', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => __( 'Left', 'elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'elebee' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'end' => [
                        'title' => __( 'Right', 'elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'stretch' => [
                        'title' => __( 'Justified', 'elementor' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => 'stretch',
                'prefix_class' => 'elementor%s-button-align-',
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => __( 'Icon', 'elementor' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => '',
            ]
        );

        $this->add_control(
            'button_icon_align',
            [
                'label' => __( 'Icon Position', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', 'elementor' ),
                    'right' => __( 'After', 'elementor' ),
                ],
                'condition' => [
                    'button_icon!' => '',
                ],
            ]
        );

        $this->add_control(
            'button_icon_indent',
            [
                'label' => __( 'Icon Spacing', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'condition' => [
                    'button_icon!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_form_texts',
            [
                'label' => __( 'Form texts', 'elebee' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $gdprLink = get_permalink( get_option( 'wp_page_for_privacy_policy' ) );
        $this->add_control(
            'comment_gdpr',
            [
                'label' => __( 'GDPR', 'elebee' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => sprintf( __( 'I have read the <a href="%s">privacy policy</a> and accept that my data will be saved for the pupose of contact or further inquiries.', 'elebee' ), $gdprLink ),
            ]
        );

        $this->add_control(
            'comment_log_in',
            [
                'label' => __( 'Login prompt', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'You must be logged in, to post a comment.', 'elebee' ),
            ]
        );

        $this->add_control(
            'comment_logged_in_as',
            [
                'label' => __( 'Logged in as text', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Logged in as', 'elebee' ),
                'description' => __( 'Use "%1$s" placeholder to display the username', 'elebee' ),
            ]
        );

        $this->add_control(
            'comment_log_out',
            [
                'label' => __( 'Log out Text', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Log out?', 'elebee' ),
                'placeholder' => __( 'Type your log out text here', 'elebee' ),
            ]
        );

        $this->add_control(
            'comment_required_sign',
            [
                'label' => __( 'Required sign', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => '*',
                'placeholder' => '*',
            ]
        );

        $this->add_control(
            'comment_required_text',
            [
                'label' => __( 'Required Text', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Required fields are marked with %s', 'elebee' ),
                'description' => __( 'Use "%s" placeholder to display the mark sign', 'elebee' ),
            ]
        );

        $this->end_controls_section();
        //</editor-fold>

        //<editor-fold desc="Elementor Tab Style">
        $this->start_controls_section(
            'section_form_style',
            [
                'label' => __( 'Form', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'row_gap',
            [
                'label' => __( 'Vertical gap between fields', 'elebee' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-column' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'column_gap',
            [
                'label' => __( 'Horizontal gap between fields', 'elebee' ),
                'description' => __( 'Affects adjacent fields.', 'elebee'  ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-column input:not([type=checkbox]) + label, {{WRAPPER}} .elementor-column input:not([type=checkbox]), 
                    {{WRAPPER}} .elementor-column textarea, {{WRAPPER}} .button[type=submit]' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 ); margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
                    '{{WRAPPER}} .elementor-column.elebee-checkbox-style > div' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 ); margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_label_style',
            [
                'label' => __( 'Label', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'label_spacing',
            [
                'label' => __( 'Spacing', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    'body.rtl {{WRAPPER}} .elebee-labels-inline label' => 'padding-left: {{SIZE}}{{UNIT}};',
                    # for the label position = inline option
                    'body:not(.rtl) {{WRAPPER}} .elebee-labels-inline label' => 'padding-right: {{SIZE}}{{UNIT}};',
                    # for the label position = inline option
                    'body {{WRAPPER}} .elebee-labels-above label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                    # for the label position = above option
                ],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-column > label' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
            ]
        );

        $this->add_control(
            'mark_required_color',
            [
                'label' => __( 'Required Mark Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} label > .required' => 'color: {{COLOR}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .elementor-column > label',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->add_control(
            'label_position',
            [
                'label' => __( 'Label position', 'elebee' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Above', 'elementor' ),
                'label_off' => __( 'Inline', 'elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_field_style',
            [
                'label' => __( 'Field', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'field_text_color',
            [
                'label' => __( 'Input Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input::placeholder, {{WRAPPER}} select::placeholder,
                    {{WRAPPER}} textarea::placeholder,
                    {{WRAPPER}} input[type=text], {{WRAPPER}} input[type=email],
					{{WRAPPER}} input[type=url], {{WRAPPER}} input[type=password],
					{{WRAPPER}}input[type=number], {{WRAPPER}} input[type=search],
					{{WRAPPER}} input[type=reset], {{WRAPPER}} input[type=tel],
					{{WRAPPER}} select, {{WRAPPER}} textarea' => 'color: {{VALUE}};',
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
                'name' => 'field_typography',
                'selector' =>
                    '{{WRAPPER}} input[type=text], {{WRAPPER}} input[type=email],
					{{WRAPPER}} input[type=url], {{WRAPPER}} input[type=password],
					{{WRAPPER}}input[type=number], {{WRAPPER}} input[type=search],
					{{WRAPPER}} input[type=reset], {{WRAPPER}} input[type=tel],
					{{WRAPPER}} select, {{WRAPPER}} textarea',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->add_control(
            'field_background_color',
            [
                'label' => __( 'Background Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} input[type=text], {{WRAPPER}} input[type=email],
					{{WRAPPER}} input[type=url], {{WRAPPER}} input[type=password],
					{{WRAPPER}}input[type=number], {{WRAPPER}} input[type=search],
					{{WRAPPER}} input[type=reset], {{WRAPPER}} input[type=tel],
					{{WRAPPER}} select, {{WRAPPER}} textarea' => 'background-color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'field_border_color',
            [
                'label' => __( 'Border Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input[type=text], {{WRAPPER}} input[type=email],
					{{WRAPPER}} input[type=url], {{WRAPPER}} input[type=password],
					{{WRAPPER}}input[type=number], {{WRAPPER}} input[type=search],
					{{WRAPPER}} input[type=reset], {{WRAPPER}} input[type=tel],
					{{WRAPPER}} select, {{WRAPPER}} textarea' => 'border-color: {{VALUE}}; color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'field_border_width',
            [
                'label' => __( 'Border Width', 'elebee' ),
                'type' => Controls_Manager::DIMENSIONS,
                'placeholder' => '1',
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} input[type=text], {{WRAPPER}} input[type=email],
					{{WRAPPER}} input[type=url], {{WRAPPER}} input[type=password],
					{{WRAPPER}}input[type=number], {{WRAPPER}} input[type=search],
					{{WRAPPER}} input[type=reset], {{WRAPPER}} input[type=tel],
					{{WRAPPER}} select, {{WRAPPER}} textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'field_border_radius',
            [
                'label' => __( 'Border Radius', 'elebee' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} input[type=text], {{WRAPPER}} input[type=email],
					{{WRAPPER}} input[type=url], {{WRAPPER}} input[type=password],
					{{WRAPPER}}input[type=number], {{WRAPPER}} input[type=search],
					{{WRAPPER}} input[type=reset], {{WRAPPER}} input[type=tel],
					{{WRAPPER}} select, {{WRAPPER}} textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_checkbox_style',
            [
                'label' => __( 'Checkbox', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'checkbox_label_spacing',
            [
                'label' => __( 'Spacing', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    'body.rtl {{WRAPPER}} label.elebee-checkbox-label' => 'padding-right: {{SIZE}}{{UNIT}};',
                    'body:not(.rtl) {{WRAPPER}} label.elebee-checkbox-label' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'checkbox_label_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elebee-checkbox-style label' => 'color: {{VALUE}};',
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
                'name' => 'checkbox_label_typography',
                'selector' => '{{WRAPPER}} .elebee-checkbox-style label',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->add_control(
            'checkbox_size',
            [
                'label' => __( 'Size', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20,
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elebee-checkbox-style input[type=checkbox]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'checkbox_margin',
            [
                'label' => __( 'Margin', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elebee-checkbox-style input[type=checkbox]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button_style',
            [
                'label' => __( 'Button', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
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
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} button[type="submit"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __( 'Typography', 'elebee' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} button[type="submit"]',
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => __( 'Background Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} button[type="submit"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'button_border',
                'label' => __( 'Border', 'elebee' ),
                'fields_options' => [
                    'border' => [
                        'default' => 'none',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '',
                            'right' => '',
                            'bottom' => '',
                            'left' => '',
                            'isLinked' => false,
                        ],
                    ],
                    'color' => [
                        'default' => '#000',
                    ],
                ],
                'selector' => '{{WRAPPER}} button[type="submit"]',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __( 'Border Radius', 'elebee' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} button[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_padding',
            [
                'label' => __( 'Text Padding', 'elebee' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} button[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selectors' => [
                    '{{WRAPPER}} button[type="submit"]:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __( 'Background Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} button[type="submit"]:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} button[type="submit"]:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'button_border_border!' => '',
                ],
            ]
        );

        $this->add_control(
            'button_hover_animation',
            [
                'label' => __( 'Animation', 'elebee' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );
        //</editor-fold>

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    /**
     * @since 0.1.0
     *
     * @return array
     */
    private function getCommentPages(): array {

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

            if ( $commentPost['ID'] == get_the_ID() ) {
                $options[$commentPost['ID']] .= __( ' (Current page)', 'elebee' );
            }

        }

        return $options;

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

        $editor = Plugin::$instance->editor;
        if ( $editor->is_edit_mode() ) {
            return;
        }

        $user = wp_get_current_user();
        $user_identity = $user->exists() ? $user->display_name : '';
        $commenter = wp_get_current_commenter();

        $fields = [
            'author' => '',
            'email' => '',
            'url' => '',
            'comment' => '',
            'cookies' => '',
            'gdpr' => '',
        ];

        $settings = $this->get_settings_for_display();

        // remove p-tag
        $settings[ 'comment_gdpr' ] = str_replace( [ '<p>', '</p>' ], '', $settings[ 'comment_gdpr' ] );
        $sign = $settings[ 'comment_required_sign' ];
        $requiredContainer = !empty( $sign ) ? '<span class="required">' . $sign . '</span>' : '';
        $fieldsSizeClass = 'elementor-field-textual elementor-size-' . $settings[ 'input_size' ];
        $labelsPosition = 'elebee-labels-' . ( $settings[ 'label_position' ] === 'yes' ?  'above' : 'inline' );

        if ( $settings[ 'show_name'] === 'yes' ) {
            $authorArgs = [
                'type' => 'author',
                'width' => !empty( $settings[ 'field_width_name' ] ) ? $settings[ 'field_width_name' ] : '100',
                'label' => $settings[ 'label_name' ],
                'placeholder' => $settings[ 'placeholder_name' ],
                'required' => $settings[ 'require_name' ] === 'yes' ? $requiredContainer : '',
                'value' => esc_attr( $commenter[ 'comment_author' ] ),
                'cssClass' => $fieldsSizeClass,
            ];

            $fields[ 'author' ] = ( new Template( __DIR__ . '/partials/input-field.php', $authorArgs ) )->getRendered();
        }

        if ( $settings[ 'show_email'] === 'yes' ) {
            $emailArgs = [
                'type' => 'email',
                'width' => !empty( $settings[ 'field_width_email' ] ) ? $settings[ 'field_width_email' ] : '100',
                'label' => $settings[ 'label_email' ],
                'placeholder' => $settings[ 'placeholder_email' ],
                'required' => $settings[ 'require_email' ] === 'yes' ? $requiredContainer : '',
                'value' => esc_attr( $commenter[ 'comment_author_email' ] ),
                'cssClass' => $fieldsSizeClass,
            ];

            $fields[ 'email' ] = ( new Template( __DIR__ . '/partials/input-field.php', $emailArgs ) )->getRendered();
        }

        if ( $settings[ 'show_extra'] === 'yes' ) {
            $extraArgs = [
                'type' => 'extra',
                'width' =>  !empty( $settings[ 'field_width_extra' ] ) ? $settings[ 'field_width_extra' ] : '100',
                'label' => $settings[ 'label_extra' ],
                'placeholder' => $settings[ 'placeholder_extra' ],
                'required' => $settings[ 'require_extra' ] === 'yes' ? $requiredContainer : '',
                // ToDo: ignore url format, if selected as a subject
                'value' => esc_attr( $commenter[ 'comment_author_url' ] ),
                'cssClass' => $fieldsSizeClass,
            ];

            $fields[ 'url' ] = ( new Template( __DIR__ . '/partials/input-field.php', $extraArgs ) )->getRendered();
        }

        if ( $settings[ 'show_gdpr_opt_in'] === 'yes' ) {
            $gdprArgs = [
                'label' => $settings[ 'comment_gdpr' ],
                'required' => $requiredContainer,
                'type' => 'gdpr',
            ];
            $fields[ 'gdpr' ] = ( new Template( __DIR__ . '/partials/checkbox.php', $gdprArgs ) )->getRendered();
        }

        if ( $settings[ 'show_cookies_opt_in'] === 'yes' ) {
            $cookiesArgs = [
                // restyle wordpress cookies consent
                'label' => __( 'Save my name, email, and website in this browser for the next time I comment.'),
                'required' => $settings[ 'show_cookies_opt_in' ] === 'yes' ? $requiredContainer : '',
                'type' => 'cookies',
            ];
            $fields[ 'cookies' ] = ( new Template( __DIR__ . '/partials/checkbox.php', $cookiesArgs ) )->getRendered();
        }

        $loggedInAsArgs = [
            'loggedInAs' => $settings['comment_logged_in_as'],
            'logOut' => $settings['comment_log_out'],
            'adminUrl' => admin_url( 'profile.php' ),
            'userIdentity' => $user_identity,
            'logOutUrl' => wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ),
        ];

        $mustLogInArgs = [
            'logIn' => $settings[ 'comment_log_in' ],
            'logInUrl' => wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
        ];

        $commentFieldArgs = [
            'fieldWidth' => !empty( $settings[ 'field_width_comment' ] ) ? $settings[ 'field_width_comment' ] : '100',
            'commentLabel' => $settings[ 'label_comment' ],
            'commentPlaceholder' => $settings[ 'placeholder_comment' ],
            'required' => '<span class="required">' . $sign . '</span>',
            'cssClass' => $fieldsSizeClass,
            'rows' => $settings[ 'rows_comment' ],
        ];


        $buttonClasses = 'elementor-field-group elementor-column elementor-field-type-submit';
        $buttonClasses .= ' elementor-col-' . ( !empty( $settings[ 'button_width' ] ) ? $settings[ 'button_width' ] : '100' );
        $submitButtonArgs = [
            'buttonClasses' => $buttonClasses,
            'buttonSize' => !empty( $settings[ 'button_size' ] ) ? $settings[ 'button_size' ] : '100',
            'buttonHoverAnimation' => $settings[ 'button_hover_animation' ],
            'buttonIcon' => $settings[ 'button_icon' ],
            'buttonIconAlign' => $settings[ 'button_icon_align' ],
            'submitText' => esc_html__( 'Submit', 'elementor' ),
            'buttonText' => $settings[ 'button_text' ],
        ];

        $comments_args = [
            'title_reply' => '',
            'title_reply_before' => '',
            'title_reply_after' => '',
            'class_form' => 'elementor-form comment-form '. $labelsPosition,
            'label_submit' => $settings[ 'button_text' ],
            'comment_notes_after' => '',
            'comment_notes_before' => '',
            'class_submit' => 'submit elementor-animation-' . $settings[ 'button_hover_animation' ],
            'fields' => apply_filters( 'comment_form_default_fields', $fields ),
            'must_log_in' => ( new Template( __DIR__ . '/partials/must-log-in.php', $mustLogInArgs ) )->getRendered(),
            'logged_in_as' => ( new Template( __DIR__ . '/partials/logged-in-as.php', $loggedInAsArgs ) )->getRendered(),
            'comment_field' => ( new Template( __DIR__ . '/partials/comment-field.php', $commentFieldArgs ) )->getRendered(),
            'submit_button' => ( new Template( __DIR__ . '/partials/submit-button.php', $submitButtonArgs ) )->getRendered(),
        ];

        comment_form( $comments_args, $settings['page'] );
    }

    /**
     * Rearrange fields in proper order.
     *
     * @param $fields array
     * @return array
     */
    public function rearrangeFields( $fields ) {

        $commentField = $fields['comment'];
        $cookiesField = $fields['cookies'];
        $gdprField = $fields['gdpr'];

        unset( $fields['comment'] );
        unset( $fields['cookies'] );
        unset( $fields['gdpr'] );

        $fields['comment'] = $commentField;
        $fields['cookies'] = $cookiesField;
        $fields['gdpr'] = $gdprField;

        return $fields;

    }

    public function skinPrintTemplate( $content, $button ) {
        if ( 'comment_form' === $button->get_name() ) {
            ob_start();
            $this->_content_template();
            $content = ob_get_clean();
        }

        return $content;
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

        echo ( new Template( __DIR__ . '/partials/editor-content.php' ) )->getRendered();

    }

    /**
     * Source: https://rudrastyh.com/wordpress/ajax-comments.html
     */
    public function ajaxCommentSubmit() {
        // array keys are pre defined
        // https://developer.wordpress.org/reference/functions/wp_handle_comment_submission/
        $commentData = [
            'comment_post_ID' => filter_input( INPUT_POST, 'comment_post_ID' ),
            'author' => filter_input( INPUT_POST, 'comment-author' ),
            'email' => filter_input( INPUT_POST, 'comment-email' ),
            'url' => filter_input( INPUT_POST, 'comment-extra' ),
            'comment' => filter_input( INPUT_POST, 'comment' ),
            'comment_parent' => filter_input( INPUT_POST, 'comment_parent' ),
        ];

        $comment = wp_handle_comment_submission( $commentData );

        if ( !is_wp_error( $comment ) ) {
            die();
        }

        $errorData = (int)$comment->get_error_data();
        if ( empty( $errorData ) ) {
            wp_die( 'Unknown error' );
        }

        wp_die( '<p>' . $comment->get_error_message() . '</p>', __( 'Comment Submission Failure' ), array( 'response' => $errorData, 'back_link' => true ) );

    }
}
