<?php

/**
 * The elementor-specific functionality of the theme.
 *
 * @since   0.3.2
 *
 * @package ElebeeCore\Elementor
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/ElebeeElementor.html
 */

namespace ElebeeCore\Elementor;


use ElebeeCore\Extensions\ResponsiveAspectRatio\ResponsiveAspectRatio;
use ElebeeCore\Extensions\Sticky\Sticky;
use ElebeeCore\Extensions\WidgetExtensionBase;
use ElebeeCore\Skins\SkinArchive;
use ElebeeCore\Widgets\Exclusive\BigAndSmallImageWithDescription\BigAndSmallImageWithDescription;
use ElebeeCore\Widgets\Exclusive\Placeholder\Placeholder;
use ElebeeCore\Widgets\Exclusive\PostTypeArchive\PostTypeArchive;
use ElebeeCore\Widgets\General\BetterAccordion\BetterAccordion;
use ElebeeCore\Widgets\General\BetterWidgetImageGallery\BetterWidgetImageGallery;
use ElebeeCore\Widgets\General\CommentForm\CommentForm;
use ElebeeCore\Widgets\General\CommentList\CommentList;
use ElebeeCore\Widgets\General\Imprint\Imprint;
use Elementor\Controls_Manager;
use Elementor\Elements_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * The elementor-specific functionality of the theme.
 *
 * Defines the theme name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since   0.3.2
 *
 * @package ElebeeCore\Elementor
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Elementor/ElebeeElementor.html
 */
class ElebeeElementor {

    /**
     * The ID of this theme.
     *
     * @since 0.3.2
     * @var   string $themeName The ID of this theme.
     */
    private $themeName;

    /**
     * @since 0.3.2
     * @var string
     */
    private $cssDirUrl;

    /**
     * @since 0.3.2
     * @var string
     */
    private $jsDirUrl;

    /**
     * The version of this theme.
     *
     * @since 0.3.2
     * @var   string $version The current version of this theme.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since 0.3.2
     *
     * @param string $themeName The name of this theme.
     * @param string $version   The version of this theme.
     */
    public function __construct( $themeName, $version ) {

        $this->themeName = $themeName;
        $this->version = $version;

        $dirUrl = untrailingslashit( get_stylesheet_directory_uri() ) . '/vendor/rto-websites/elebee-core/src/Elementor';
        $this->cssDirUrl = $dirUrl . '/css';
        $this->jsDirUrl = $dirUrl . '/js';

    }

    /**
     * @since 0.3.2
     *
     * @return void
     */
    public function enqueueEditorStyles() {

        wp_enqueue_style( $this->themeName . '-editor', $this->cssDirUrl . '/editor.css', [], $this->version, 'all' );

    }

    /**
     * @since 0.3.2
     *
     * @return void
     */
    public function enqueueEditorScripts() {

        wp_enqueue_script( $this->themeName . '-editor', $this->jsDirUrl . '/editor.js', [ 'jquery' ], $this->version, true );
        wp_localize_script( $this->themeName . '-editor', 'themeVars', [
            'themeUrl' => get_stylesheet_directory_uri(),
        ] );

    }

    /**
     * @since 0.3.2
     *
     * @return void
     */
    public function enqueuePreviewScripts() {

        wp_enqueue_script( $this->themeName . '-preview', $this->jsDirUrl . '/preview.js', [ 'jquery' ], $this->version, true );

    }

    /**
     * @since 0.3.2
     *
     * @return void
     */
    public function setupOverrides() {

        require_once dirname( __DIR__ ) . '/overrides/Elementor/Shapes.php';

    }

    /**
     * @since 0.3.2
     *
     * @return void
     */
    public function setupExtensions() {

        $sticky = new Sticky();
        $sticky->register( Controls_Manager::TAB_ADVANCED, 'section', 'section_custom_css', WidgetExtensionBase::NEW_SECTION_AFTER, false, 50 );

        $imageExtension = new ResponsiveAspectRatio();
        $imageExtension->register( Controls_Manager::TAB_STYLE, 'image', 'section_style_image', WidgetExtensionBase::EXTEND_SECTION_AFTER, true );

        $googleMapsExtension = new ResponsiveAspectRatio();
        $googleMapsExtension->register( Controls_Manager::TAB_CONTENT, 'google_maps', 'section_map', WidgetExtensionBase::EXTEND_SECTION_AFTER, true );

        if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
            require_once dirname( __DIR__ ) . '/Extensions/FormFields/FormFields.php';

            $slidesExtension = new ResponsiveAspectRatio();
            $slidesExtension->register( Controls_Manager::TAB_CONTENT, 'slides', 'section_slides', WidgetExtensionBase::EXTEND_SECTION_AFTER, true );

        }

    }

    /**
     * Register Widget
     *
     * @since 0.3.2
     *
     * @return void
     */
    public function registerWidgets() {

        Plugin::instance()->widgets_manager->register_widget_type( new Imprint() );
        Plugin::instance()->widgets_manager->register_widget_type( new BetterWidgetImageGallery() );
        Plugin::instance()->widgets_manager->register_widget_type( new CommentForm() );
        Plugin::instance()->widgets_manager->register_widget_type( new CommentList() );
        Plugin::instance()->widgets_manager->register_widget_type( new BetterAccordion() );

    }

    /**
     * Register Widget
     *
     * @since 0.3.2
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
     * @since 0.3.2
     *
     * @return void
     */
    public function setupCategories( Elements_Manager $elementsManager ) {

        $elementsManager->add_category(
            'rto-elements',
            [
                'title' => __( 'RTO Elements', 'elebee' ),
                'icon' => 'font',
            ]
        );

        $elementsManager->add_category(
            'rto-elements-exclusive',
            [
                'title' => __( 'RTO Elements - Exclusive', 'elebee' ),
                'icon' => 'font',
            ]
        );

    }

    /**
     * @since 0.3.2
     *
     * @param Widget_Base $widget
     *
     * @return void
     */
    public function registerSkinArchive( Widget_Base $widget ) {

        $widget->add_skin( new SkinArchive( $widget ) );

    }

}
