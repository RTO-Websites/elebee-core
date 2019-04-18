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


use ElebeeCore\Admin\Setting\Google\Analytics\SettingAnonymizeIp;
use ElebeeCore\Admin\Setting\Google\Analytics\SettingTrackingId;
use ElebeeCore\Admin\Setting\SettingJQuery;
use ElebeeCore\Lib\Util\Template;

\defined( 'ABSPATH' ) || exit;

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
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function enqueueStyles() {

        wp_enqueue_style( 'main', get_stylesheet_directory_uri() . '/css/main.min.css', [], $this->version, 'all' );

        if ( is_user_logged_in() ) {
            # common.css file cause faulty display in the gallery lightbox
            wp_deregister_style( 'elementor-common' );
        }

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function enqueueScripts() {

        $settingJQuery = ( new SettingJQuery() )->getOption();
        switch ( $settingJQuery ) {
            case 'latest-cdn':
                wp_deregister_script( 'jquery' );
                wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', [], '3.3.1' );
                break;
            case 'latest-local':
                wp_deregister_script( 'jquery' );
                wp_register_script( 'jquery', get_stylesheet_directory_uri() . '/vendor/rto-websites/elebee-core/src/public/assets/js/jquery.min.js', [], '3.3.1' );
                break;
        }

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

    public function embedGoogleAnalytics() {
        $trackingId = ( new SettingTrackingId() )->getOption();

        if( empty( $trackingId ) ) {
            return;
        }

        $googleAnalyticsTemplate = new Template( __DIR__ . '/partials/google-analytics.php', [
            'gaTrackingId' => $trackingId,
            'anonymizeIp' => ( new SettingAnonymizeIp() )->getOption() ? 'true' : 'false',
        ] );
        $googleAnalyticsTemplate->render();

    }

}
