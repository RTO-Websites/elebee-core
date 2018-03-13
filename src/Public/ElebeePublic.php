<?php
/**
 * The public-facing functionality of the theme.
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Pub
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Pub/ElebeePublic.html
 */

namespace ElebeeCore\Pub;


use ElebeeCore\Extensions\GlobalSettings\WidgetPadding;
use ElebeeCore\Extensions\ResponsiveAspectRatio\ResponsiveAspectRatio;
use ElebeeCore\Extensions\Slides\Slides;
use ElebeeCore\Extensions\Sticky\Sticky;
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
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * The public-facing functionality of the theme.
 *
 * Defines the theme name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Pub
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Pub/ElebeePublic.html
 */
class ElebeePublic {

    /**
     * The ID of this theme.
     *
     * @since 0.1.0
     * @var string The ID of this theme.
     */
    private $themeName;

    /**
     * The version of this theme.
     *
     * @since 0.1.0
     * @var string $version The current version of this theme.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since 0.1.0
     *
     * @param string $themeName The name of the theme.
     * @param string $version   The version of this theme.
     */
    public function __construct( string $themeName, string $version ) {

        $this->themeName = $themeName;
        $this->version = $version;

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public function setupElementorCategories() {

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
     *
     * @return void
     */
    public function registerWidgets() {

        Plugin::instance()->widgets_manager->register_widget_type( new Imprint() );
        Plugin::instance()->widgets_manager->register_widget_type( new BetterWidgetImageGallery() );
        Plugin::instance()->widgets_manager->register_widget_type( new AspectRatioImage() );
        Plugin::instance()->widgets_manager->register_widget_type( new CommentForm() );
        Plugin::instance()->widgets_manager->register_widget_type( new CommentList() );
        Plugin::instance()->widgets_manager->register_widget_type( new BetterAccordion() );

    }

    /**
     * @since 0.1.0
     *
     * @param Widget_Base $widget
     *
     * @return void
     */
    public function addWidgetPostsSkins( Widget_Base $widget ) {

        $widget->add_skin( new SkinArchive( $widget ) );
    }

    /**
     * Register Widget
     *
     * @since 0.1.0
     *
     * @return void
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
     * @since 0.1.0
     *
     * @return void
     */
    public function enqueueStyles() {

        wp_enqueue_style( 'main', get_stylesheet_directory_uri() . '/css/main.min.css', [], $this->version, 'all' );

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function enqueueScripts() {

        wp_deregister_script( 'jquery' );
        wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', [], '3.3.1' );

        wp_enqueue_script( $this->themeName . '-vendor', get_stylesheet_directory_uri() . '/js/vendor.min.js', [ 'jquery' ], $this->version, true );
        wp_enqueue_script( $this->themeName . '-main', get_stylesheet_directory_uri() . '/js/main.min.js', [ 'jquery', $this->themeName . '-vendor' ], $this->version, true );
        wp_localize_script( $this->themeName . '-main', 'themeVars', [
            'websiteName' => get_bloginfo( 'name' ),
            'websiteUrl' => esc_url( get_site_url() ),
            'themeUrl' => esc_url( get_stylesheet_directory_uri() ),
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'isSearch' => number_format( is_search() ),
            'isMobile' => number_format( wp_is_mobile() ),
            'debug' => number_format( WP_DEBUG ),
        ] );

        if ( WP_DEBUG ) {
            wp_enqueue_script( 'livereload', '//localhost:35729/livereload.js' );
        }

    }

    public function setupElementorOverrides() {

        require_once dirname( __DIR__ ) . '/overrides/Elementor/Shapes.php';

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public function setupElementorExtensions() {

        $sticky = new Sticky( Controls_Manager::TAB_ADVANCED );
        $sticky->addRegistration( 'section', 'section_custom_css', 'after_section_end', 50 );
        $sticky->register();

        $responsiveAspectRatio = new ResponsiveAspectRatio( Controls_Manager::TAB_STYLE );
        $responsiveAspectRatio->addRegistration( 'image', 'section_style_image', 'after_section_end' );
        $responsiveAspectRatio->register();

        if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
            require_once dirname( __DIR__ ) . '/Extensions/FormFields/FormFields.php';

            $slides = new Slides( Controls_Manager::TAB_CONTENT );
            $slides->addRegistration( 'slides', 'section_slides', 'before_section_end' );
            $slides->register();

        }

        do_action( 'rto_init_extensions' );

    }

}
