<?php
/**
 * @since 0.1.0
 * @author RTO GmbH <info@rto.de>
 * @licence MIT
 */

namespace ElebeeCore\Lib\PostTypeSupport;


use ElebeeCore\Lib\Hooking;

class PostTypeSupportExcerpt {

    use Hooking;

    /**
     * @since 0.1.0
     */
    public function defineAdminHooks() {}

    /**
     * @since 0.1.0
     */
    public function definePublicHooks() {

        $this->getLoader()->addAction( 'after_setup_theme', $this, 'addPostTypeSupportExcerpt' );

    }

    /**
     * @since 0.1.0
     */
    public function addPostTypeSupportExcerpt() {

        add_post_type_support( 'page', 'excerpt', true );

    }
}