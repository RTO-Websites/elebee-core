<?php
/**
 * WidgetPlaceholder.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Elementor\Widgets\Placeholder
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/Placeholder/WidgetPlaceholder.html
 */

namespace ElebeeCore\Elementor\Widgets\Placeholder;


use Elementor\Controls_Manager;
use Elementor\Widget_Base;

\defined( 'ABSPATH' ) || exit;

/**
 * Class WidgetPlaceholder
 *
 * @since   0.1.0
 *
 * @package ElebeeCoreElementor\Elementor\Widgets\Placeholder
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/Widgets/Placeholder/WidgetPlaceholder.html
 */
class WidgetPlaceholder extends Widget_Base {

    /**
     * Retrieve image widget name.
     *
     * @since 0.1.0
     *
     * @return string Widget name.
     */
    public function get_name(): string {

        return 'placeholder';

    }

    /**
     * Retrieve image widget title.
     *
     * @since 0.1.0
     *
     * @return string Widget title.
     */
    public function get_title(): string {

        return __( 'Placeholder', 'elebee' );

    }

    /**
     * Retrieve image widget icon.
     *
     * @since 0.1.0
     *
     * @return string Widget icon.
     */
    public function get_icon(): string {

        return 'fa fa-star-o';

    }

    /**
     * Retrieve the list of categories the image gallery widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since 0.1.0
     *
     * @return array Widget categories.
     */
    public function get_categories(): array {

        return [ 'rto-elements-exclusive' ];

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
     * @since 0.1.0
     *
     * @return void
     */
    protected function render() {

        $settings = $this->get_settings();
        echo $settings['placeholder'];

    }
}