<?php

namespace ElebeeCore\Widgets\Exclusive\Placeholder;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Placeholder extends Widget_Base {

    /**
     * Retrieve image widget name.
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {

        return 'placeholder';

    }

    /**
     * Retrieve image widget title.
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {

        return __( 'Placeholder', 'elebee' );

    }

    /**
     * Retrieve image widget icon.
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {

        return 'fa fa-star-o';

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

        return [ 'rto-elements-exclusive' ];

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
            'section_placeholder',
            [
                'label' => __( 'Placeholder', 'elebee' ),
            ]
        );

        $this->add_control(
            'placeholder',
            [
                'label' => __( 'Placeholder', 'elebee' ),
                'type' => Controls_Manager::TEXT,
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

        $settings = $this->get_settings();
        echo $settings['placeholder'];

    }
}