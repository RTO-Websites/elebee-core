<?php
/**
 * @since 0.1.0
 * @author RTO GmbH <info@rto.de>
 * @licence MIT
 */

namespace ElebeeCore\Lib;


use ElebeeCore\Admin\ElebeeAdmin;
use ElebeeCore\Lib\ThemeSupport\ThemeSupportExcerpt;
use ElebeeCore\Lib\ThemeSupport\ThemeSupportFeaturedImage;
use ElebeeCore\Lib\ThemeSupport\ThemeSupportHTML5;
use ElebeeCore\Pub\ElebeePublic;
use Elementor\Settings;

class Elebee {

    /**
     * The current version of the theme.
     *
     * @since    0.1.0
     * @access   public
     * @var      const    VERSION    The current version of the theme.
     */
    const VERSION = '0.1.0';

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the theme.
     *
     * @since    0.1.0
     * @access   private
     * @var      ElebeeLoader $loader Maintains and registers all hooks for the theme.
     */
    private $loader;

    /**
     * The unique identifier of this theme.
     *
     * @since    0.1.0
     * @access   private
     * @var      string $themeName The string used to uniquely identify this theme.
     */
    private $themeName;

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
        $this->setupThemeSupport();
        $this->defineAdminHooks();
        $this->definePublicHooks();

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
     * @access   private
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
     * @since    0.1.0
     * @access   private
     */
    private function setLocale() {

        $elebeeI18n = new ElebeeI18n();
        $elebeeI18n->setDomain( $this->getThemeName() );
        $elebeeI18n->loadThemeTEXTDOMAIN();

    }

    /**
     * @since 0.1.0
     */
    private function setupThemeSupport() {

        $themeSupportHTML5 = new ThemeSupportHTML5();
        $themeSupportHTML5->getLoader()->run();

        $themeSupportExcerpt = new ThemeSupportExcerpt();
        $themeSupportExcerpt->getLoader()->run();

        $themeSupportFeaturedImage = new ThemeSupportFeaturedImage();
        $themeSupportFeaturedImage->getLoader()->run();

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the theme.
     *
     * @since    0.1.0
     * @access   private
     */
    private function defineAdminHooks() {

        $elebeeAdmin = new ElebeeAdmin( $this->getThemeName(), $this->getVersion() );

        $this->loader->addAction( 'admin_enqueue_scripts', $elebeeAdmin, 'enqueueStyles' );
        $this->loader->addAction( 'admin_enqueue_scripts', $elebeeAdmin, 'enqueueScripts' );

        $this->loader->addAction( 'elementor/editor/before_enqueue_styles', $elebeeAdmin, 'enqueueEditorStyles' );
        $this->loader->addAction( 'elementor/editor/before_enqueue_scripts', $elebeeAdmin, 'enqueueEditorScripts', 99999 );

        $this->loader->addAction( 'wp_ajax_get_post_id_by_url', $elebeeAdmin, 'get_post_id_by_url' );

        $this->loader->addAction( 'admin_init', $elebeeAdmin, 'settingsApiInit' );
        $this->loader->addAction( 'admin_menu', $elebeeAdmin, 'addMenuPage', Settings::MENU_PRIORITY_GO_PRO + 1 );

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the theme.
     *
     * @since    0.1.0
     * @access   private
     */
    private function definePublicHooks() {

        $elebeePublic = new ElebeePublic( $this->getThemeName(), $this->getVersion() );

        $this->loader->addAction( 'init', $elebeePublic, 'loadExtensions' );

        $this->loader->addAction( 'elementor/init', $elebeePublic, 'elementorInit' );

        $this->loader->addAction( 'elementor/widgets/widgets_registered', $elebeePublic, 'registerWidgets' );
        $this->loader->addAction( 'elementor/widgets/widgets_registered', $elebeePublic, 'registerExclusiveWidgets' );

        $this->loader->addAction( 'elementor/widget/posts/skins_init', $elebeePublic, 'addWidgetPostsSkins' );

        $this->loader->addAction( 'elementor/frontend/after_register_scripts', $elebeePublic, 'enqueueStyles' );
        $this->loader->addAction( 'elementor/frontend/after_register_scripts', $elebeePublic, 'enqueueScripts' );

    }

    /**
     * The name of the theme used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     0.1.0
     * @return    string    The name of the theme.
     */
    public function getThemeName() {
        return $this->themeName;
    }

    /**
     * The reference to the class that orchestrates the hooks with the theme.
     *
     * @since     0.1.0
     * @return    ElebeeLoader    Orchestrates the hooks of the theme.
     */
    public function getLoader() {

        return $this->loader;

    }

    /**
     * Retrieve the version number of the theme.
     *
     * @since     0.1.0
     * @return    string    The version number of the theme.
     */
    public function getVersion() {

        return self::VERSION;

    }

    /**
     *
     */
    public function phpVersionFail() {

        $message = esc_html__( 'The Elementor RTO plugin requires PHP version 7.0+, plugin is currently NOT ACTIVE.', TEXTDOMAIN );
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
     * @since    0.1.0
     */
    public static function run() {

        $elebee = new self();

        if ( ! version_compare( PHP_VERSION, '7.0', '>=' ) ) {

            $elebee->getLoader()->addAction( 'admin_notices', $elebee, 'phpVersionFail' );

        }

        $elebee->getLoader()->run();

    }

}