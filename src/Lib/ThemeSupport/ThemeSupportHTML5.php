<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence GPL-3.0
 */

namespace ElebeeCore\Lib\ThemeSupport;


use ElebeeCore\Lib\Hooking;

class ThemeSupportHTML5 {

    use Hooking;

    /**
     * @since 0.1.0
     */
    public function defineAdminHooks() {}

    /**
     * @since 0.1.0
     */
    public function definePublicHooks() {

        $this->getLoader()->addAction( 'after_setup_theme', $this, 'addThemeSupportHTML5' );

    }

    /**
     * @since 0.1.0
     */
    public function addThemeSupportHTML5() {

        $args = [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ];
        add_theme_support( 'html5', $args );

    }

}