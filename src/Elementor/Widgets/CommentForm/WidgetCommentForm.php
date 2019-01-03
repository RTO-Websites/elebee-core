<?php
/**
 * WidgetCommentForm.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\CommentForm
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/CommentForm/WidgetCommentForm.html
 */

namespace ElebeeCore\Elementor\Widgets\CommentForm;


use ElebeeCore\Elementor\Widgets\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;

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
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/CommentForm/WidgetCommentForm.html
 */
class WidgetCommentForm extends WidgetBase {

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

        $this->start_controls_section(
            'section_comments',
            [
                'label' => __( 'Comment Form', 'elebee' ),
            ]
        );

        $this->add_control(
            'page',
            [
                'label' => __( 'Page', 'elementor-pro' ),
                'description' => __( 'Comments get posted to the selected page.', 'elebee' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'default' => get_the_ID(),
                'options' => $this->getCommentPages(),
            ]
        );

        $this->add_control(
            'comment_title',
            [
                'label' => __( 'Title', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Write a Reply or Comment', 'elebee' ),
                'placeholder' => __( 'Type your comment from title text here', 'elebee' ),
            ]
        );

        $this->add_responsive_control(
            'comment_title_align',
            [
                'label' => __( 'Alignment Title', 'elebee' ),
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
                    '{{WRAPPER}} .comment-reply-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'comment_log_in',
            [
                'label' => __( 'Log in Text', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'You must be logged in to post a comment.', 'elebee' ),
                'placeholder' => __( 'Type your log in as text here', 'elebee' ),
            ]
        );

        $this->add_control(
            'comment_logged_in_as',
            [
                'label' => __( 'Logged in Text', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Logged in as', 'elebee' ),
                'placeholder' => __( 'Type your logged in as text here', 'elebee' ),
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
            'comment_required_text',
            [
                'label' => __( 'Required Text', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Required fields are marked %s', 'elebee' ),
                'placeholder' => __( '%s is placeholder for sign', 'elebee' ),
            ]
        );

        $this->add_responsive_control(
            'comment_log_in_out_align',
            [
                'label' => __( 'Alignment Log in/out', 'elebee' ),
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
                    '{{WRAPPER}} .logged-in-as, {{WRAPPER}} .must-log-in' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'comment_label',
            [
                'label' => __( 'Comment', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Comment', 'elebee' ),
                'placeholder' => __( 'Type your comment label text here', 'elebee' ),
            ]
        );

        $this->add_control(
            'comment_button',
            [
                'label' => __( 'Send Button Text', 'elebee' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Send', 'elebee' ),
                'placeholder' => __( 'Type your send button text here', 'elebee' ),
            ]
        );

        $this->add_responsive_control(
            'comment_button_align',
            [
                'label' => __( 'Alignment Button', 'elebee' ),
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
                    '{{WRAPPER}} .form-submit' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_form_style',
            [
                'label' => __( 'Form', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'column_gap',
            [
                'label' => __( 'Columns Gap', 'elementor-pro' ),
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
                    '{{WRAPPER}} .elementor-field-group, {{WRAPPER}} .form-submit' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
                    '{{WRAPPER}} .comment-form ' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
                ],
            ]
        );

        $this->add_control(
            'row_gap',
            [
                'label' => __( 'Rows Gap', 'elementor-pro' ),
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
                    '{{WRAPPER}} .elementor-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __( 'Title', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'comment_title_color',
            [
                'label' => __( 'Title', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .comment-reply-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'comment_title_typography',
                'selector' => '{{WRAPPER}} .comment-reply-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_text_style',
            [
                'label' => __( 'Text', 'elebee' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'comment_log_color',
            [
                'label' => __( 'Log in/out Text Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .logged-in-as, {{WRAPPER}} .must-log-in' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'comment_log_typography',
                'selector' => '{{WRAPPER}} .logged-in-as, {{WRAPPER}} .must-log-in',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->start_controls_tabs( 'tabs_links_style' );

        $this->start_controls_tab(
            'tab_links_normal',
            [
                'label' => __( 'Normal', 'elebee' ),
            ]
        );

        $this->add_control(
            'comment_log_link_color',
            [
                'label' => __( 'Link Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .logged-in-as a, {{WRAPPER}} .must-log-in a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_links_hover',
            [
                'label' => __( 'Hover', 'elebee' ),
            ]
        );

        $this->add_control(
            'comment_log_link_hover_color',
            [
                'label' => __( 'HoverColor', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .logged-in-as a:hover, {{WRAPPER}} .must-log-in a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'comment_log_typography_b',
                'selector' => '{{WRAPPER}} .logged-in-as, {{WRAPPER}} .must-log-in',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
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
            'field_label_text_color',
            [
                'label' => __( 'Label Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} label' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
            ]
        );

        $this->add_control(
            'field_text_color',
            [
                'label' => __( 'Input Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input[type=text], {{WRAPPER}} input[type=email],
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
                'selector' => '{{WRAPPER}} input[type=text], {{WRAPPER}} input[type=email],
					{{WRAPPER}} input[type=url], {{WRAPPER}} input[type=password],
					{{WRAPPER}}input[type=number], {{WRAPPER}} input[type=search],
					{{WRAPPER}} input[type=reset], {{WRAPPER}} input[type=tel],
					{{WRAPPER}} select, {{WRAPPER}} textarea, 
					{{WRAPPER}} label',
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

        $this->add_responsive_control(
            'field_width',
            [
                'label' => __( 'Field Width', 'elementor-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Default', 'elementor-pro' ),
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
            'comment_field_width',
            [
                'label' => __( 'Comment Field Width', 'elementor-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'Default', 'elementor-pro' ),
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
                    '{{WRAPPER}} #submit.submit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __( 'Typography', 'elebee' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} #submit.submit',
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
                    '{{WRAPPER}} #submit.submit' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'button_border',
                'label' => __( 'Border', 'elebee' ),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} #submit.submit',
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
                    '{{WRAPPER}} #submit.submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} #submit.submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} #submit.submit:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __( 'Background Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #submit.submit:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'elebee' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #submit.submit:hover' => 'border-color: {{VALUE}};',
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

        $settings = $this->get_settings();
        $user = wp_get_current_user();
        $user_identity = $user->exists() ? $user->display_name : '';
        $commenter = wp_get_current_commenter();
        $req = get_option( 'require_name_email' );
        $required_text = sprintf( ' ' . __( $settings['comment_required_text'] ), '<span class="required">*</span>' );
        $aria_req = ( $req ? " aria-required='true'" : '' );

        if ( empty( $settings['field_width'] ) ) {
            $settings['field_width'] = '100';
        }

        if ( empty( $settings['comment_field_width'] ) ) {
            $settings['comment_field_width'] = '100';
        }

        $fields = [

            'author' =>
                '<div class="elementor-field-group elementor-column elementor-col-' . $settings['field_width'] . '">
				<label for="author">' . __( 'Name', 'elebee' ) . '</label> ' .
                ( $req ? '<span class="required">*</span>' : '' ) .
                '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
                '" size="30"' . $aria_req . ' /></div>',

            'email' =>
                '<div class="elementor-field-group elementor-column elementor-col-' . $settings['field_width'] . '">
				<label for="email">' . __( 'Email', 'elebee' ) . '</label> ' .
                ( $req ? '<span class="required">*</span>' : '' ) .
                '<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) .
                '" size="30"' . $aria_req . ' /></div>',

            'url' =>
                '<div class="elementor-field-group elementor-column elementor-col-' . $settings['field_width'] . '">
				<label for="url">' . __( 'Website', 'elebee' ) . '</label>' .
                '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
                '" size="30" /></div>',
        ];

        $comments_args = [
            'label_submit' => $settings['comment_button'],
            'title_reply' => $settings['comment_title'],
            'comment_notes_after' => '',
            'logged_in_as' => '<div class="elementor-field-group elementor-column"><p class="logged-in-as">' . sprintf( __( $settings['comment_logged_in_as'] . ' <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">' . $settings['comment_log_out'] . '</a>', 'elebee' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p></div>',
            'must_log_in' => '<div class="elementor-field-group elementor-column"><p class="must-log-in">' . sprintf( __( '<a href="%s">' . $settings['comment_log_in'] . '</a>' ), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p></div>',
            'comment_notes_before' => '<div class="elementor-field-group elementor-column elementor-col-100"><p class="comment-notes">' . __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) . '</p></div>',
            'class_submit' => 'submit elementor-animation-' . $settings['button_hover_animation'],
            'fields' => apply_filters( 'comment_form_default_fields', $fields ),
            'comment_field' => '<div class="elementor-field-group elementor-column elementor-col-' . $settings['comment_field_width'] . '"><label for="comment">' . _x( $settings['comment_label'], 'noun' ) . '</label><br /><textarea id="comment" name="comment" aria-required="true"></textarea></p></div>',
        ];


        add_filter( 'comment_form_fields', function ( $fields ) {

            $comment_field = $fields['comment'];
            unset( $fields['comment'] );
            $fields['comment'] = $comment_field;
            return $fields;
        } );

        comment_form( $comments_args, $settings['page'] );

    }

}
