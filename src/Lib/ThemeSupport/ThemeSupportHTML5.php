<?php
/**
 * ThemeSupportHTML5.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib\ThemeSupport
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeSupport/ThemeSupportHTML5.html
 */

namespace ElebeeCore\Lib\ThemeSupport;


\defined( 'ABSPATH' ) || exit;

/**
 * Class ThemeSupportHTML5
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib\ThemeSupport
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeSupport/ThemeSupportHTML5.html
 */
class ThemeSupportHTML5 extends ThemeSupportBase {

    /**
     * @since 0.1.0
     */
    public function addThemeSupport() {

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