<?php
/**
 * Config.php
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\Util
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Util/Config.html
 */

namespace ElebeeCore\Lib\Util;


/**
 * Class Config
 *
 * @since   0.2.0
 *
 * @package ElebeeCore\Lib\Util
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/Util/Config.html
 */
class Config {

    /**
     * @since 0.2.0
     *
     * @param string $header
     * @return string
     */
    public static function disableRedirectGuess( string $header ): string {

        global $wp_query;

        if ( is_404() )
            unset( $wp_query->query_vars['name'] );

        return $header;

    }

    /**
     * @since 0.2.0
     *
     * @return void
     */
    public static function disableEmojies() {

        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

    }

    /**
     * @since 0.2.0
     *
     * @param $plugins
     * @return array
     */
    public static function disableTinymceEmojies( $plugins ) {

        if ( !is_array( $plugins ) ) {
            return [];
        }

        return array_diff( $plugins, [
            'wpemoji',
        ] );

    }

    /**
     * @since 0.2.0
     *
     * @return void
     */
    public static function cleanUpHead() {

        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wp_generator' );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'index_rel_link' );
        remove_action( 'wp_head', 'start_post_rel_link', 10 );
        remove_action( 'wp_head', 'parent_post_rel_link', 10 );
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
        remove_action( 'wp_head', 'feed_links', 2 );
        remove_action( 'wp_head', 'feed_links_extra', 3 );
        remove_action( 'wp_head', 'wp_shosrtlink_wp_head', 10 );
        remove_action( 'wp_head', 'wp_shortlink_header', 10 );

    }

    /**
     * @since 0.2.0
     *
     * @param array $settings
     * @return array
     */
    public static function switchTinymceEnterMode( array $settings ) {

        $formats = preg_replace( '/([{,])\s*(\w+)\s*:/i', '$1"$2":', $settings['formats'] );
        $formats = json_decode( $formats, true );
        $formats['removeformat'] = [
            [
                'selector' => 'b,strong,em,i,font,u,strike',
                'remove' => 'all',
                'split' => true,
                'expand' => false,
                'block_expand' => true,
                'deep' => true,
            ],
            [
                'selector' => 'span',
                'attributes' => [ 'style', 'class' ],
                'remove' => 'empty',
                'split' => true,
                'expand' => false,
                'deep' => true,
            ],
            [
                'selector' => '*',
                'attributes' => [ 'style', 'class' ],
                'split' => false,
                'expand' => false,
                'deep' => true,
            ],
        ];
        $settings['formats'] = json_encode( $formats );

        $settings['forced_root_block'] = false;
        $settings["force_p_newlines"] = true;
        $settings["elementpath"] = true;

        return $settings;

    }

}