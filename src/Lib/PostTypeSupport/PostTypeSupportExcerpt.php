<?php
/**
 * PostTypeSupportExcerpt.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib\PostTypeSupport
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/PostTypeSupport/PostTypeSupportExcerpt.html
 */

namespace ElebeeCore\Lib\PostTypeSupport;


/**
 * Class PostTypeSupportExcerpt
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib\PostTypeSupport
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/PostTypeSupport/PostTypeSupportExcerpt.html
 */
class PostTypeSupportExcerpt extends PostTypeSupport {

    /**
     * PostTypeSupportExcerpt constructor.
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
    public function hookCallback() {

        add_post_type_support( 'page', 'excerpt', true );

    }
}