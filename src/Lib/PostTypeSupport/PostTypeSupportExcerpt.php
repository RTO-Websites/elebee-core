<?php
/**
 * @since 0.1.0
 * @author RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 */

namespace ElebeeCore\Lib\PostTypeSupport;


class PostTypeSupportExcerpt extends PostTypeSupport {

    /**
     * PostTypeSupportExcerpt constructor.
     * @param string $hook
     */
    public function __construct( string $hook = 'after_setup_theme' ) {

        parent::__construct( $hook );

    }

    /**
     * @since 0.1.0
     */
    public function hookCallback() {

        add_post_type_support( 'page', 'excerpt', true );

    }
}