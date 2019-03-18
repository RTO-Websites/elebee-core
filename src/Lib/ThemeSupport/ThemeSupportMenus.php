<?php
/**
 * ThemeSupportMenus.php
 *
 * @since   0.3.2
 *
 * @package ElebeeCore\Lib\ThemeSupport
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeSupport/ThemeSupportMenus.html
 */

namespace ElebeeCore\Lib\ThemeSupport;


\defined( 'ABSPATH' ) || exit;

/**
 * ThemeSupportMenus.php
 *
 * @since   0.1.0
 *
 * @package ElebeeCore\Lib\ThemeSupport
 * @author  RTO GmbH <info@rto.de>
 * @licence GPL-3.0
 * @link    https://rto-websites.github.io/elebee-core-api/master/ElebeeCore/Lib/ThemeSupport/ThemeSupportMenus.html
 */
class ThemeSupportMenus extends ThemeSupportBase {

    public function addThemeSupport() {

        add_theme_support( 'menus' );

    }

}