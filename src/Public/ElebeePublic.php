<?php namespace ElebeeCore\Pub;

use ElebeeCore\Extensions\Slides\Slides;
use ElebeeCore\Skins\SkinArchive;
use ElebeeCore\Widgets\Exclusive\BigAndSmallImageWithDescription\BigAndSmallImageWithDescription;
use ElebeeCore\Widgets\Exclusive\Placeholder\Placeholder;
use ElebeeCore\Widgets\Exclusive\PostTypeArchive\PostTypeArchive;
use ElebeeCore\Widgets\General\AspectRatioImage\AspectRatioImage;
use ElebeeCore\Widgets\General\BetterAccordion\BetterAccordion;
use ElebeeCore\Widgets\General\BetterWidgetImageGallery\BetterWidgetImageGallery;
use ElebeeCore\Widgets\General\CommentForm\CommentForm;
use ElebeeCore\Widgets\General\CommentList\CommentList;
use ElebeeCore\Widgets\General\Imprint\Imprint;
use ElebeeCore\Extensions\Sticky\Sticky;
use Elementor;
use Elementor\Plugin;
use Elementor\Widget_Base;

/**
 * The public-facing functionality of the theme.
 *
 * @link       https://www.rto.de/
 * @since      0.1.0
 *
 * @package    Elebee
 * @subpackage Elebee/public
 */

/**
 * The public-facing functionality of the theme.
 *
 * Defines the theme name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Elebee
 * @subpackage Elebee/public
 * @author     RTO GmbH <info@rto.de>
 */
class ElebeePublic {

    /**
     * The ID of this theme.
     *
     * @since    0.1.0
     * @access   private
     * @var      string $themeName The ID of this theme.
     */
    private $themeName;

    /**
     * The version of this theme.
     *
     * @since    0.1.0
     * @access   private
     * @var      string $version The current version of this theme.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    0.1.0
     * @param      string $themeName The name of the theme.
     * @param      string $version The version of this theme.
     */
    public function __construct( $themeName, $version ) {

        $this->themeName = $themeName;
        $this->version = $version;

    }

    /**
     *
     */
    public function elementorInit() {

        $elementor = Plugin::$instance;

        // Add element category in panel
        $elementor->elements_manager->add_category(
            'rto-elements',
            [
                'title' => __( 'RTO Elements', 'elebee' ),
                'icon' => 'font',
            ],
            1
        );

        $elementor->elements_manager->add_category(
            'rto-elements-exclusive',
            [
                'title' => __( 'RTO Elements - Exclusive', 'elebee' ),
                'icon' => 'font',
            ],
            2
        );

    }

    /**
     * Register Widget
     *
     * @since 0.1.0
     */
    public function registerWidgets() {

        Plugin::instance()->widgets_manager->register_widget_type( new Imprint() );
        Plugin::instance()->widgets_manager->register_widget_type( new BetterWidgetImageGallery() );
        Plugin::instance()->widgets_manager->register_widget_type( new AspectRatioImage() );
        Plugin::instance()->widgets_manager->register_widget_type( new CommentForm() );
        Plugin::instance()->widgets_manager->register_widget_type( new CommentList() );
        Plugin::instance()->widgets_manager->register_widget_type( new BetterAccordion() );

    }

    public function addWidgetPostsSkins( Widget_Base $widget ) {

        $widget->add_skin( new SkinArchive( $widget ) );
    }

    /**
     * Register Widget
     *
     * @since 0.1.0
     */
    public function registerExclusiveWidgets() {

        if ( !get_option( 'is_exclusive' ) ) {
            return;
        }

        Plugin::instance()->widgets_manager->register_widget_type( new BigAndSmallImageWithDescription() );
        Plugin::instance()->widgets_manager->register_widget_type( new Placeholder() );
        Plugin::instance()->widgets_manager->register_widget_type( new PostTypeArchive() );
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    0.1.0
     */
    public function enqueueStyles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in ElebeeLoader as all of the hooks are defined
         * in that particular class.
         *
         * The ElebeeLoader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style( 'main', get_stylesheet_directory_uri() . '/css/main.min.css', [], $this->version, 'all' );

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    0.1.0
     */
    public function enqueueScripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in ElebeeLoader as all of the hooks are defined
         * in that particular class.
         *
         * The ElebeeLoader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script( 'vendor', get_stylesheet_directory_uri() . '/js/vendor.min.js', [ 'jquery' ], $this->version, true );
        wp_enqueue_script( 'main-min', get_stylesheet_directory_uri() . '/js/main.min.js', [ 'jquery' ], $this->version, true );
        wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Public/js/main.js', [ 'jquery' ], $this->version, true );
//        wp_localize_script( $this->themeName, 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

        if ( WP_DEBUG ) {
            wp_enqueue_script( 'livereload', '//localhost:35729/livereload.js' );
        }
    }

    /**
     *
     */
    public function loadExtensions() {

        if ( defined( 'ELEMENTOR_PATH' ) &&
            class_exists( 'Elementor\Plugin' ) &&
            is_callable( 'Elementor\Plugin', 'instance' ) ) {

            $elementor = Elementor\Plugin::instance();
            if ( isset( $elementor->widgets_manager ) && method_exists( $elementor->widgets_manager, 'register_widget_type' ) ) {
                require_once dirname( __DIR__ ) . '/overrides/Elementor/Shapes.php';
                require_once dirname( __DIR__ ) . '/overrides/Elementor/Core/Settings/General/Model.php';

                // only with elementor pro:
                if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
                    require_once dirname( __DIR__ ) . '/Extensions/FormFields/FormFields.php';

                    $slides = new Slides();
                    $slides->getLoader()->run();

                }

                do_action( 'rto_init_extensions' );
            }

        }

        $sticky = new Sticky();
        $sticky->getLoader()->run();

    }
}
