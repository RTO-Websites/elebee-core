<?php
/**
 * ThemeSupportFeaturedImage.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib\ThemeSupport
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeSupport/ThemeSupportFeaturedImage.html
 */

namespace ElebeeCore\Lib\ThemeSupport;


defined( 'ABSPATH' ) || exit;

/**
 * Class ThemeSupportFeaturedImage
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib\ThemeSupport
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeSupport/ThemeSupportFeaturedImage.html
 */
class ThemeSupportFeaturedImage extends ThemeSupportBase {

    /**
     * ThemeSupportFeaturedImage constructor.
     *
     * @since 0.1.0
     *
     * @param string $hook
     */
    public function __construct( string $hook = 'after_setup_theme' ) {

        parent::__construct( $hook );

    }

    /**
     * @since 0.1.0
     */
    public function defineAdminHooks() {

        parent::defineAdminHooks();

        $this->getLoader()->addFilter( 'manage_posts_columns', $this, 'customColumns' );
        $this->getLoader()->addFilter( 'manage_pages_columns', $this, 'customColumns' );

        $this->getLoader()->addAction( 'manage_posts_custom_column', $this, 'customColumnsData', 10, 2 );
        $this->getLoader()->addAction( 'manage_pages_custom_column', $this, 'customColumnsData', 10, 2 );

    }

    /**
     * @since 0.2.0
     */
    public function hookCallback() {

        add_theme_support( 'post-thumbnails' );

//        $featuredImageSize = get_option( 'bambee_featured_images', $this->postThumbnail );

//        set_post_thumbnail_size(
//            $featuredImageSize['width'],
//            $featuredImageSize['height'],
//            $featuredImageSize['crop']
//        );
        set_post_thumbnail_size( 624, 624, false );

    }

    /**
     * Add the featured image column to the Wordpress tables.
     *
     * @since 0.1.0
     *
     * @param array $columns
     * @return array
     */
    public function customColumns( array $columns ): array {

        $offset = array_search( 'date', array_keys( $columns ) );

        return array_merge(
            array_slice( $columns, 0, $offset ),
            [ 'featured_image' => __( 'Featured Image' ) ],
            array_slice( $columns, $offset, null )
        );

    }

    /**
     * Prints data in a column.
     *
     * @since 0.1.0
     *
     * @param string $column
     * @param int    $postId
     * @return void
     */
    public function customColumnsData( string $column, int $postId ) {

        switch ( $column ) {
            case 'featured_image':
                echo the_post_thumbnail( 'thumbnail' );
                break;
        }

    }

}