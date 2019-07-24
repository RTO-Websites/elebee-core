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


use ElebeeCore\Admin\Setting\SettingIsExclusive;
use ElebeeCore\Elementor\Extensions\Fitty\WidgetExtensionFitty;
use ElebeeCore\Elementor\Extensions\ResponsiveAspectRatio\WidgetExtensionResponsiveAspectRatio;
use ElebeeCore\Elementor\Extensions\Sticky\WidgetExtensionSticky;
use ElebeeCore\Elementor\Extensions\WidgetExtensionBase;
use ElebeeCore\Elementor\Skins\SkinArchive;
use ElebeeCore\Elementor\Widgets\BetterAccordion\WidgetBetterAccordion;
use ElebeeCore\Elementor\Widgets\BetterImageGallery\WidgetBetterImageGallery;
use ElebeeCore\Elementor\Widgets\BigAndSmallImageWithDescription\WidgetBigAndSmallImageWithDescription;
use ElebeeCore\Elementor\Widgets\CommentForm\WidgetCommentForm;
use ElebeeCore\Elementor\Widgets\CommentList\WidgetCommentList;
use ElebeeCore\Elementor\Widgets\Imprint\WidgetImprint;
use ElebeeCore\Elementor\Widgets\Placeholder\WidgetPlaceholder;
use ElebeeCore\Elementor\Widgets\PostTypeArchive\WidgetPostTypeArchive;
use ElebeeCore\Elementor\Widgets\WidgetImageGallery\WidgetImageGallery;
use ElebeeCore\Elementor\Widgets\WidgetImageCarousel\WidgetImageCarousel;
use Elementor\Controls_Manager;
use Elementor\Elements_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

\defined( 'ABSPATH' ) || exit;

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

        $assetsUrl = untrailingslashit( get_stylesheet_directory_uri() ) . '/vendor/rto-websites/elebee-core/src/Elementor/assets';
        $this->cssDirUrl = $assetsUrl . '/css';
        $this->jsDirUrl = $assetsUrl . '/js';

    }

    /**
     * @since 0.3.2
     *
     * @return void
     */
    public function enqueueEditorStyles() {

        wp_enqueue_style( $this->themeName . '-editor', get_stylesheet_directory_uri() . '/css/editor.min.css', [], $this->version, 'all' );

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
    public function enqueuePreviewStyles() {

        wp_enqueue_style( $this->themeName . '-preview', get_stylesheet_directory_uri() . '/css/preview.min.css', [], $this->version, 'all' );

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
    public function setupExtensions() {

        $sticky = new WidgetExtensionSticky();
        $sticky->register( Controls_Manager::TAB_ADVANCED, 'section', 'section_custom_css', WidgetExtensionBase::NEW_SECTION_AFTER, false, 50 );

        $imageExtension = new WidgetExtensionResponsiveAspectRatio();
        $imageExtension->register( Controls_Manager::TAB_STYLE, 'image', 'section_style_image', WidgetExtensionBase::EXTEND_SECTION_AFTER, true );

        $titleExtension = new WidgetExtensionFitty();
        $titleExtension->register( Controls_Manager::TAB_CONTENT, 'heading', 'section_title', WidgetExtensionBase::EXTEND_SECTION_AFTER );

        $googleMapsExtension = new WidgetExtensionResponsiveAspectRatio();
        $googleMapsExtension->register( Controls_Manager::TAB_CONTENT, 'google_maps', 'section_map', WidgetExtensionBase::EXTEND_SECTION_AFTER, true );

        if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
            require_once dirname( __DIR__ ) . '/Elementor/Extensions/FormFields/FormFields.php';

            $slidesExtension = new WidgetExtensionResponsiveAspectRatio();
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

        Plugin::instance()->widgets_manager->register_widget_type( new WidgetImprint() );
        Plugin::instance()->widgets_manager->register_widget_type( new WidgetBetterImageGallery() );
        Plugin::instance()->widgets_manager->register_widget_type( new WidgetCommentForm() );
        Plugin::instance()->widgets_manager->register_widget_type( new WidgetCommentList() );
        Plugin::instance()->widgets_manager->register_widget_type( new WidgetBetterAccordion() );
        Plugin::instance()->widgets_manager->register_widget_type( new WidgetImageGallery() );
        Plugin::instance()->widgets_manager->register_widget_type( new WidgetImageCarousel() );

    }

    /**
     * Register Widget
     *
     * @since 0.3.2
     *
     * @return void
     */
    public function registerExclusiveWidgets() {

        if ( !( new SettingIsExclusive() )->getOption() ) {
            return;
        }

        Plugin::instance()->widgets_manager->register_widget_type( new WidgetBigAndSmallImageWithDescription() );
        Plugin::instance()->widgets_manager->register_widget_type( new WidgetPlaceholder() );
        Plugin::instance()->widgets_manager->register_widget_type( new WidgetPostTypeArchive() );

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
                'title' => __( 'Elebee Elements', 'elebee' ),
                'icon' => 'font',
            ]
        );

        $elementsManager->add_category(
            'rto-elements-exclusive',
            [
                'title' => __( 'Elebee Elements - Exclusive', 'elebee' ),
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
