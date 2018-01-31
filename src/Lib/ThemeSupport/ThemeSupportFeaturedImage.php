<?php
/**
 * @since 0.1.0
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Lib\ThemeSupport;


class ThemeSupportFeaturedImage extends ThemeSupport {

    /**
     * ThemeSupportFeaturedImage constructor.
     * @param string $hook
     */
    public function __construct( string $hook = 'after_setup_theme' ) {

        parent::__construct( $hook );

    }

    /**
     * @since    0.1.0
     */
    public function defineAdminHooks() {

        $this->getLoader()->addFilter( 'manage_posts_columns', $this, 'customColumns' );
        $this->getLoader()->addFilter( 'manage_pages_columns', $this, 'customColumns' );

        $this->getLoader()->addAction( 'manage_posts_custom_column', $this, 'customColumnsData', 10, 2 );
        $this->getLoader()->addAction( 'manage_pages_custom_column', $this, 'customColumnsData', 10, 2 );

    }

    /**
     * @inheritdoc
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
     * @param $columns
     *
     * @return array
     */
    public function customColumns( $columns ) {

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
     * @param $column
     * @param $postId
     *
     * @return void
     */
    public function customColumnsData( $column, $postId ) {

        switch ( $column ) {
            case 'featured_image':
                echo the_post_thumbnail( 'thumbnail' );
                break;
        }

    }

}