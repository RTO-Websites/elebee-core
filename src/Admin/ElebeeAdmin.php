<?php

/**
 * The admin-specific functionality of the theme.
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Admin
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Admin/ElebeeAdmin.html
 */

namespace ElebeeCore\Admin;


use ElebeeCore\Lib\Template;
use Elementor\Settings;

/**
 * The admin-specific functionality of the theme.
 *
 * Defines the theme name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Admin
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Admin/ElebeeAdmin.html
 */
class ElebeeAdmin {

    /**
     * The ID of this theme.
     *
     * @since 0.1.0
     * @var   string $themeName The ID of this theme.
     */
    private $themeName;

    /**
     * The version of this theme.
     *
     * @since 0.1.0
     * @var   string $version The current version of this theme.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since 0.1.0
     *
     * @param string $themeName The name of this theme.
     * @param string $version   The version of this theme.
     */
    public function __construct( $themeName, $version ) {

        $this->themeName = $themeName;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since  0.1.0
     *
     * @return void
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

        wp_enqueue_style( $this->themeName, get_stylesheet_directory_uri() . '/css/admin.min.css', [], $this->version, 'all' );
        wp_enqueue_style( $this->themeName . '-elementor', get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Admin/css/elementor-rto-admin.css', [], $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since 0.1.0
     *
     * @return void
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

        wp_enqueue_script( $this->themeName, get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Admin/js/elementor-rto-admin.js', [ 'jquery' ], $this->version, false );

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public function enqueueEditorStyles() {

        wp_enqueue_style( $this->themeName . '-editor', get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Admin/css/elementor-rto-editor.css', [], $this->version, 'all' );

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public function enqueueEditorScripts() {

        wp_enqueue_script( $this->themeName . '-editor', get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/Admin/js/elementor-rto-editor.js', [ 'jquery' ], $this->version, true );
        wp_localize_script( $this->themeName . '-editor', 'elementor_rto_url', get_stylesheet_directory_uri() );

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public function get_post_id_by_url() {

        $url = url_to_postid( $_POST['url'] );
        wp_send_json(
            [
                'postId' => $url,
                'path' => get_site_url(),
            ]
        );
        die();

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public function settingsApiInit() {

//        add_settings_section(
//            'elementor_rto_default_section',
//            __('Settings', 'elebee'),
//            [$this, 'sectionCallback'],
//            'elementor_rto_settings'
//        );

        add_settings_field(
            'is_exclusive',
            __( 'Is Exclusive', 'elebee' ),
            [ $this, 'settingCallback' ],
            'elementor_rto_settings'
        );

        register_setting( 'elementor_rto_settings', 'is_exclusive' );

    }

    public function settingCallback() {

        echo '<input name="is_exclusive" id="is_exclusive" type="checkbox" value="1" ' . checked( 1, get_option( 'is_exclusive' ), false ) . '>';

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public function addMenuPage() {

        add_submenu_page(
            Settings::PAGE_ID,
            __( 'RTO Settings', 'elebee' ),
            __( 'RTO Settings', 'elebee' ),
            'manage_options',
            'elementor_rto_settings',
            [ $this, 'renderAdminPage' ]
        );

    }

    /**
     * @since 0.1.0
     *
     * @return void
     */
    public function renderAdminPage() {

        ( new Template( dirname( __DIR__ ) . '/admin/partials/elementor-rto-admin-display.php' ) )->render();

    }

}
