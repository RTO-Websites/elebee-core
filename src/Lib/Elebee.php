<?php
/**
 * Elebee.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Elebee.html
 */

namespace ElebeeCore\Lib;


use ElebeeCore\Admin\ElebeeAdmin;
use ElebeeCore\Extensions\ResponsiveAspectRatio\ResponsiveAspectRatio;
use ElebeeCore\Extensions\Sticky\Sticky;
use ElebeeCore\Extensions\WidgetExtensionBase;
use ElebeeCore\Lib\CustomPostType\CustomCss\CustomCss;
use ElebeeCore\Lib\PostTypeSupport\PostTypeSupportExcerpt;
use ElebeeCore\Lib\ThemeCustomizer\Section;
use ElebeeCore\Lib\ThemeCustomizer\Setting;
use ElebeeCore\Lib\ThemeCustomizer\ThemeCustommizer;
use ElebeeCore\Lib\ThemeSupport\ThemeSupportFeaturedImage;
use ElebeeCore\Lib\ThemeSupport\ThemeSupportHTML5;
use ElebeeCore\Lib\ThemeSupport\ThemeSupportMenus;
use ElebeeCore\Lib\ThemeSupport\ThemeSupportTitleTag;
use ElebeeCore\Lib\Util\Config;
use ElebeeCore\Pub\ElebeePublic;
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
use Elementor\Plugin;
use Elementor\Settings;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Class Elebee
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Elebee.html
 */
class Elebee {

    /**
     * The current version of the theme.
     *
     * @since 0.1.0
     * @var string The current version of the theme.
     */
    const VERSION = '0.3.1';

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the theme.
     *
     * @since 0.1.0
     * @var ElebeeLoader Maintains and registers all hooks for the theme.
     */
    private $loader;

    /**
     * The unique identifier of this theme.
     *
     * @since 0.1.0
     * @var string The string used to uniquely identify this theme.
     */
    private $themeName;

    /**
     * @since 0.2.0
     * @var ThemeCustommizer
     */
    private $themeCustomizer;

    /**
     * Define the core functionality of the theme.
     *
     * Set the theme name and the theme version that can be used throughout the theme.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    0.1.0
     */
    public function __construct() {

        $this->themeName = 'elebee';

        $this->loadDependencies();
        $this->setLocale();
        $this->setupPostTypeSupport();
        $this->setupThemeSupport();
        $this->setupThemeCustomizer();
        $this->setupCustomPostTypes();
        $this->defineAdminHooks();
        $this->definePublicHooks();
        $this->defineGeneralHooks();

    }

    /**
     * Load the required dependencies for this theme.
     *
     * Include the following files that make up the theme:
     *
     * - ElebeeLoader. Orchestrates the hooks of the theme.
     * - ElebeeI18n. Defines internationalization functionality.
     * - ElebeeAdmin. Defines all hooks for the admin area.
     * - ElebeePublic. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    0.1.0
     *
     * @return void
     */
    private function loadDependencies() {

        $this->loader = new ElebeeLoader();

    }

    /**
     * Define the locale for this theme for internationalization.
     *
     * Uses the ElebeeI18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since 0.1.0
     *
     * @return void
     */
    private function setLocale() {

        $elebeeI18n = new ElebeeI18n();
        $elebeeI18n->setDomain( $this->getThemeName() );
        $elebeeI18n->loadThemeTEXTDOMAIN();

    }

    /**
     * @since 0.2.0
     *
     * @return void
     */
    private function setupPostTypeSupport() {

        $themeSupportExcerpt = new PostTypeSupportExcerpt();
        $themeSupportExcerpt->addPostTypeSupport();

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    private function setupThemeSupport() {

        $themeSupportMenus = new ThemeSupportMenus();
        $themeSupportMenus->getLoader()->run();

        $themeSupportTitleTag = new ThemeSupportTitleTag();
        $themeSupportTitleTag->getLoader()->run();

        $themeSupportHTML5 = new ThemeSupportHTML5();
        $themeSupportHTML5->getLoader()->run();

        $themeSupportFeaturedImage = new ThemeSupportFeaturedImage();
        $themeSupportFeaturedImage->getLoader()->run();

    }

    /**
     * @since 0.3.0
     *
     * @return void
     */
    private function setUpCustomPostTypes() {

        $globalCustomCss = new CustomCss();
        $globalCustomCss->getLoader()->run();

    }

    /**
     * @since 0.2.0
     *
     * @return void
     */
    public function setupThemeCustomizer() {

        $this->themeCustomizer = new ThemeCustommizer();
        $this->setupThemeSettingsCoreData();
        $this->themeCustomizer->register();

    }

    /**
     * @since 0.2.0
     *
     * @return void
     */
    public function setupThemeSettingsCoreData() {

        $settingCoreDataAddress = new Setting(
            'elebee_core_data_address',
            [
                'type' => 'option',
                'default' => '',
            ],
            [
                'label' => __( 'Address', 'elebee' ),
                'description' => __( '[coredata]address[/coredata]', 'elebee' ),
                'type' => 'textarea',
            ]
        );

        $settingCoreDataEmail = new Setting(
            'elebee_core_data_email',
            [
                'type' => 'option',
                'default' => '',
            ],
            [
                'label' => __( 'E-Mail address', 'elebee' ),
                'description' => __( '[coredata]email[/coredata]', 'elebee' ),
                'type' => 'text',
            ]
        );

        $settingCoreDataPhone = new Setting(
            'elebee_core_data_phone',
            [
                'type' => 'option',
                'default' => '',
            ],
            [
                'label' => __( 'Phone', 'elebee' ),
                'description' => __( '[coredata]phone[/coredata]', 'elebee' ),
                'type' => 'text',
            ]
        );

        $description = __( 'You can use the [coredata]key[/coredata] shortcode to display the core data field inside a post.', 'elebee' );
        $sectionCoreData = new Section( 'elebee_core_data_section', [
            'title' => __( 'Core data', 'elebee' ),
            'priority' => 700,
            'description' => $description,
        ] );
        $sectionCoreData->addSetting( $settingCoreDataAddress );
        $sectionCoreData->addSetting( $settingCoreDataEmail );
        $sectionCoreData->addSetting( $settingCoreDataPhone );

        $this->themeCustomizer->addSection( $sectionCoreData );

    }

    /**
     * @since 0.3.2
     *
     * @return void
     */
    public function setupElementorOverrides() {

        require_once dirname( __DIR__ ) . '/overrides/Elementor/Shapes.php';

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
     * @since 0.3.2
     *
     * @return void
     */
    public function setupElementorExtensions() {

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

        do_action( 'rto_init_extensions' );

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
     * @param Widget_Base $widget
     *
     * @return void
     */
    public function addWidgetPostsSkins( Widget_Base $widget ) {

        $widget->add_skin( new SkinArchive( $widget ) );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the theme.
     *
     * @since 0.1.0
     *
     * @return void
     */
    private function defineAdminHooks() {

        $this->loader->addAction( 'tiny_mce_before_init', Config::class, 'switchTinymceEnterMode' );
        $this->loader->addFilter( 'tiny_mce_plugins', Config::class, 'disableTinymceEmojies' );

        $elebeeAdmin = new ElebeeAdmin( $this->getThemeName(), $this->getVersion() );

        $this->loader->addAction( 'admin_init', $elebeeAdmin, 'settingsApiInit' );
        if ( class_exists( 'Elementor\Settings' ) ) {
            $this->loader->addAction( 'admin_menu', $elebeeAdmin, 'addMenuPage', Settings::MENU_PRIORITY_GO_PRO + 1 );
        }

        $this->loader->addAction( 'admin_enqueue_scripts', $elebeeAdmin, 'enqueueStyles', 100 );
        $this->loader->addAction( 'admin_enqueue_scripts', $elebeeAdmin, 'enqueueScripts' );

        $this->loader->addAction( 'elementor/editor/before_enqueue_styles', $elebeeAdmin, 'enqueueEditorStyles' );
        $this->loader->addAction( 'elementor/editor/before_enqueue_scripts', $elebeeAdmin, 'enqueueEditorScripts', 99999 );

        $this->loader->addAction( 'elementor/preview/enqueue_scripts', $elebeeAdmin, 'enqueuePreviewScripts' );

        $this->loader->addAction( 'wp_ajax_get_post_id_by_url', $elebeeAdmin, 'getPostIdByUrl' );

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the theme.
     *
     * @since 0.1.0
     *
     * @return void
     */
    private function definePublicHooks() {

        $this->loader->addAction( 'init', Config::class, 'cleanUpHead' );
        $this->loader->addAction( 'init', Config::class, 'disableEmojies' );
        $this->loader->addFilter( 'status_header', Config::class, 'disableRedirectGuess' );

        $htmlCompression = new HtmlCompression();
        $this->loader->addAction( 'get_header', $htmlCompression, 'start' );

        $elebeePublic = new ElebeePublic( $this->getThemeName(), $this->getVersion() );

        $this->loader->addAction( 'elementor/frontend/after_register_scripts', $elebeePublic, 'enqueueStyles' );
        $this->loader->addAction( 'elementor/frontend/after_register_scripts', $elebeePublic, 'enqueueScripts' );

    }

    /**
     * @since 0.3.2
     *
     * @return void
     */
    private function defineGeneralHooks() {

        $this->loader->addAction( 'elementor/init', $this, 'setupElementorOverrides' );
        $this->loader->addAction( 'elementor/init', $this, 'setupElementorCategories' );
        $this->loader->addAction( 'elementor/init', $this, 'setupElementorExtensions' );
        $this->loader->addAction( 'elementor/init', $this, 'registerWidgets' );
        $this->loader->addAction( 'elementor/init', $this, 'registerExclusiveWidgets' );
        $this->loader->addAction( 'elementor/widget/posts/skins_init', $this, 'addWidgetPostsSkins' );

    }

    /**
     * The name of the theme used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since 0.1.0
     *
     * @return string The name of the theme.
     */
    public function getThemeName() {

        return $this->themeName;
    }

    /**
     * The reference to the class that orchestrates the hooks with the theme.
     *
     * @since 0.1.0
     *
     * @return ElebeeLoader Orchestrates the hooks of the theme.
     */
    public function getLoader() {

        return $this->loader;

    }

    /**
     * Retrieve the version number of the theme.
     *
     * @since 0.1.0
     *
     * @return string The version number of the theme.
     */
    public function getVersion() {

        return self::VERSION;

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public function phpVersionFail() {

        $message = esc_html__( 'The Elementor RTO plugin requires PHP version 7.0+, plugin is currently NOT ACTIVE.', 'elebee' );
        $errorTemplate = new Template( dirname( __DIR__ ) . '/public/partials/general/element-default.php', [
            'tag' => 'div',
            'attributes' => 'class="error"',
            'content' => wpautop( $message ),
        ] );
        $errorTemplate->render();

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public static function run() {

        $elebee = new self();

        if ( !version_compare( PHP_VERSION, '7.0', '>=' ) ) {

            $elebee->getLoader()->addAction( 'admin_notices', $elebee, 'phpVersionFail' );

        }

        $elebee->getLoader()->run();

    }

}